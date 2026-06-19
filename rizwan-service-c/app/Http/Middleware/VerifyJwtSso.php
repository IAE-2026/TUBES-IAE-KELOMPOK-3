<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Firebase\JWT\JWT;
use Firebase\JWT\JWK;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Str;
use Exception;

class VerifyJwtSso
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $authorizationHeader = $request->header('Authorization');

        if (!$authorizationHeader || !str_starts_with($authorizationHeader, 'Bearer ')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized: Token JWT tidak ditemukan pada header Authorization.',
                'errors' => null
            ], 401);
        }

        $token = substr($authorizationHeader, 7);

        try {
            // Fetch and cache JWKS from SSO domain (cache for 24 hours)
            $ssoUrl = env('IAE_SSO_URL', 'https://iae-sso.virtualfri.id');
            $jwks = Cache::remember('iae_sso_jwks', 86400, function () use ($ssoUrl) {
                $response = Http::get($ssoUrl . '/api/v1/auth/jwks');
                if (!$response->successful()) {
                    throw new Exception('Gagal mengambil JWKS dari server SSO terpusat.');
                }
                return $response->json();
            });

            // Decode token using JWKS (firebase/php-jwt parsing keyset)
            $decoded = JWT::decode($token, JWK::parseKeySet($jwks));

            // Extract user information
            $email = $decoded->profile->email ?? ($decoded->email ?? ($decoded->sub ?? null));
            $name = $decoded->profile->name ?? ($decoded->name ?? ($decoded->username ?? 'Warga SSO'));
            $roleName = $decoded->profile->role ?? ($decoded->role ?? 'warga');

            if (!$email) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized: Payload token tidak valid (email tidak ditemukan).',
                    'errors' => null
                ], 401);
            }

            // Map user and role to local database
            $role = Role::firstOrCreate(['name' => $roleName]);

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => bcrypt(Str::random(16)), // Dummy password since authentication is offloaded to SSO
                    'role_id' => $role->id
                ]
            );

            // Authenticate user session in Laravel context
            auth()->login($user);

            // Store attributes in request for controller/GraphQL use if needed
            $request->attributes->set('sso_user', $user);
            $request->attributes->set('jwt_payload', $decoded);

            return $next($request);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized: Token JWT tidak valid atau telah kedaluwarsa.',
                'errors' => [
                    'details' => $e->getMessage()
                ]
            ], 401);
        }
    }
}
