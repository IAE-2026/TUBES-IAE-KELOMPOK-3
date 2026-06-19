<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SsoService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.sso.base_url'), '/');
        $this->apiKey = config('services.sso.api_key');
    }

    /**
     * Fetch M2M Access Token from SSO with caching
     */
    public function getM2mToken(): string
    {
        return Cache::remember('sso_m2m_token', 3000, function () {
            $response = Http::post($this->baseUrl . '/api/v1/auth/token', [
                'api_key' => $this->apiKey,
            ]);

            if ($response->failed()) {
                throw new \Exception('Failed to fetch M2M token from SSO: ' . $response->body());
            }

            $data = $response->json();
            if (empty($data['token'])) {
                throw new \Exception('SSO token response does not contain a token.');
            }

            return $data['token'];
        });
    }

    /**
     * Fetch JWKS public keys with caching
     */
    protected function getJwks(): array
    {
        return Cache::remember('sso_jwks', 86400, function () {
            $response = Http::get($this->baseUrl . '/.well-known/jwks.json');

            if ($response->failed()) {
                // Try alternate endpoint if the default fails
                $response = Http::get($this->baseUrl . '/api/v1/auth/jwks');
            }

            if ($response->failed()) {
                throw new \Exception('Failed to fetch JWKS keys from SSO.');
            }

            return $response->json();
        });
    }

    /**
     * Decode and verify JWT token
     */
    public function decodeAndVerifyToken(string $token): object
    {
        $jwks = $this->getJwks();
        $keys = JWK::parseKeySet($jwks);

        // Firebase JWT decode parses using matching kid
        return JWT::decode($token, $keys);
    }

    /**
     * Map JWT payload to local user and role
     */
    public function mapUserAndRole(object $payload): User
    {
        $tokenType = $payload->token_type ?? 'user';
        $email = '';
        $name = '';
        $roleName = '';

        if ($tokenType === 'user') {
            $email = $payload->profile->email ?? ($payload->sub ?? '');
            $name = $payload->profile->name ?? 'SSO User';
            $roleName = $payload->profile->role ?? 'warga';
        } elseif ($tokenType === 'm2m') {
            $email = ($payload->sub ?? 'm2m') . '@m2m.iae.id';
            $name = $payload->app->name ?? 'SSO M2M App';
            $roleName = 'm2m';
        } else {
            $email = $payload->sub ?? '';
            $name = 'SSO Client';
            $roleName = 'user';
        }

        if (empty($email)) {
            throw new \Exception('JWT payload does not contain a valid email or subject.');
        }

        // 1. Find or create Role
        $role = Role::firstOrCreate(
            ['name' => strtolower($roleName)],
            ['description' => 'Role mapped from SSO Central (' . $roleName . ')']
        );

        // 2. Find or create User and map to Role
        $user = User::where('email', $email)->first();
        if ($user) {
            $user->update([
                'name' => $name,
                'role_id' => $role->id,
            ]);
        } else {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt(\Illuminate\Support\Str::random(16)), // Dummy password for SSO mapped users
                'role_id' => $role->id,
            ]);
        }

        return $user;
    }
}
