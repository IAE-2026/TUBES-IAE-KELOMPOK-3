<?php

namespace App\Http\Middleware;

use App\Services\SsoService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    protected SsoService $ssoService;

    public function __construct(SsoService $ssoService)
    {
        $this->ssoService = $ssoService;
    }

    /**
     * Handle an incoming request.
     * Memvalidasi API Key dari header X-IAE-KEY atau JWT SSO dari header Authorization.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check legacy X-IAE-KEY
        $apiKey = $request->header('X-IAE-KEY');
        if (!empty($apiKey) && $apiKey === config('app.api_key')) {
            return $next($request);
        }

        // 2. Check SSO JWT Bearer Token
        $token = $request->bearerToken();
        if (!empty($token)) {
            try {
                $decoded = $this->ssoService->decodeAndVerifyToken($token);
                $user = $this->ssoService->mapUserAndRole($decoded);
                
                // Login the user locally for the request context
                Auth::login($user);
                
                return $next($request);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized. Invalid SSO Token: ' . $e->getMessage(),
                    'errors' => null,
                ], 401);
            }
        }

        // 3. Fallback: neither is provided/valid
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized. API Key (X-IAE-KEY) atau SSO Bearer Token tidak valid atau tidak ditemukan.',
            'errors' => null,
        ], 401);
    }
}
