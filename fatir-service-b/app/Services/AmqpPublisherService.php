<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AmqpPublisherService
{
    protected SsoService $ssoService;
    protected string $publishUrl;

    public function __construct(SsoService $ssoService)
    {
        $this->ssoService = $ssoService;
        $this->publishUrl = rtrim(config('services.sso.base_url'), '/') . '/api/v1/messages/publish';
    }

    /**
     * Publish JSON event notification to RabbitMQ exchange
     */
    public function publishEvent(string $routingKey, array $message, ?string $bearerToken = null): bool
    {
        try {
            // Get Bearer token (either forwarded or M2M)
            $token = $bearerToken ?: $this->ssoService->getM2mToken();

            // POST JSON event to RabbitMQ HTTP gateway
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ])->post($this->publishUrl, [
                'routing_key' => $routingKey,
                'message' => $message,
            ]);

            if ($response->failed()) {
                Log::error('RabbitMQ Publish Failed: ' . $response->body());
                return false;
            }

            return true;

        } catch (\Exception $e) {
            Log::error('RabbitMQ Publish Exception: ' . $e->getMessage());
            return false;
        }
    }
}
