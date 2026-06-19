<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SoapClientService
{
    protected SsoService $ssoService;
    protected string $auditUrl;
    protected string $teamId;

    public function __construct(SsoService $ssoService)
    {
        $this->ssoService = $ssoService;
        $this->auditUrl = rtrim(config('services.sso.base_url'), '/') . '/soap/v1/audit';
        $this->teamId = config('services.sso.team_id');
    }

    /**
     * Send critical transaction log to SOAP audit service
     */
    public function auditTransaction(string $activityName, array $data, ?string $bearerToken = null): ?string
    {
        try {
            // Get Bearer token (either forwarded or M2M)
            $token = $bearerToken ?: $this->ssoService->getM2mToken();

            // Construct rigid XML payload
            $xmlPayload = $this->buildSoapEnvelope($activityName, $data);

            // POST to SOAP service
            $response = Http::withHeaders([
                'Content-Type' => 'text/xml; charset=utf-8',
                'Authorization' => 'Bearer ' . $token,
            ])->withBody($xmlPayload, 'text/xml')->post($this->auditUrl);

            if ($response->failed()) {
                Log::error('SOAP Audit Request Failed: ' . $response->body());
                return null;
            }

            return $this->parseReceiptNumber($response->body());

        } catch (\Exception $e) {
            Log::error('SOAP Audit Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Build the XML SOAP Envelope
     */
    protected function buildSoapEnvelope(string $activityName, array $data): string
    {
        $jsonLogContent = json_encode($data);
        
        return '<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:iae="http://iae.central/audit">
  <soap:Body>
    <iae:AuditRequest>
      <iae:TeamID>' . htmlspecialchars($this->teamId) . '</iae:TeamID>
      <iae:ActivityName>' . htmlspecialchars($activityName) . '</iae:ActivityName>
      <iae:LogContent><![CDATA[' . $jsonLogContent . ']]></iae:LogContent>
    </iae:AuditRequest>
  </soap:Body>
</soap:Envelope>';
    }

    /**
     * Parse ReceiptNumber from XML Response
     */
    protected function parseReceiptNumber(string $xmlContent): ?string
    {
        if (preg_match('/<iae:ReceiptNumber>(.*?)<\/iae:ReceiptNumber>/', $xmlContent, $matches)) {
            return $matches[1];
        }
        
        return null;
    }
}
