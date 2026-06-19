<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\InboundShipment;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use App\Services\IaeCentralService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Firebase\JWT\JWT;
use Firebase\JWT\JWK;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Str;
use Exception;

class InboundShipmentController extends Controller
{
    private function formatResponse($status, $message, $data = null, $code = 200)
    {
        $response = [
            'status' => $status,
            'message' => $message,
        ];

        if ($status === 'success') {
            $response['data'] = $data;
            $response['meta'] = [
                'service_name' => 'Expedition-Service',
                'api_version' => 'v1'
            ];
        } else {
            $response['errors'] = $data;
        }

        return response()->json($response, $code);
    }

    private function verifyJwtAndGetUser(Request $request)
    {
        $authorizationHeader = $request->header('Authorization');

        if (!$authorizationHeader || !str_starts_with($authorizationHeader, 'Bearer ')) {
            return null;
        }

        $token = substr($authorizationHeader, 7);

        try {
            $ssoUrl = env('IAE_SSO_URL', 'https://iae-sso.virtualfri.id');
            $jwks = Cache::remember('iae_sso_jwks', 86400, function () use ($ssoUrl) {
                $response = Http::get($ssoUrl . '/api/v1/auth/jwks');
                if (!$response->successful()) {
                    throw new Exception('Gagal mengambil JWKS dari server SSO terpusat.');
                }
                return $response->json();
            });

            $decoded = JWT::decode($token, JWK::parseKeySet($jwks));

            $email = $decoded->profile->email ?? ($decoded->email ?? ($decoded->sub ?? null));
            $name = $decoded->profile->name ?? ($decoded->name ?? ($decoded->username ?? 'Warga SSO'));
            $roleName = $decoded->profile->role ?? ($decoded->role ?? 'warga');

            if (!$email) {
                return null;
            }

            $role = Role::firstOrCreate(['name' => $roleName]);

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => bcrypt(Str::random(16)),
                    'role_id' => $role->id
                ]
            );

            return $user;
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('JWT Verification failed: ' . $e->getMessage(), ['exception' => $e]);
            file_put_contents(storage_path('logs/debug_verify.log'), $e->getMessage() . "\n" . $e->getTraceAsString());
            return null;
        }
    }

    #[OA\Get(path: "/api/v1/inbound-shipments", summary: "Mengambil daftar seluruh kargo", tags: ["Shipments"], security: [["ApiKeyAuth" => []]])]
    #[OA\Response(response: 200, description: "Berhasil mengambil data")]
    public function index(Request $request)
    {
        $user = $this->verifyJwtAndGetUser($request);
        if (!$user) {
            return $this->formatResponse('error', 'Unauthorized: Token JWT tidak valid atau tidak ditemukan.', null, 401);
        }

        $shipments = InboundShipment::all();
        return $this->formatResponse('success', 'Data armada logistik berhasil diambil', $shipments);
    }

    #[OA\Get(path: "/api/v1/inbound-shipments/{id}", summary: "Melacak status spesifik kargo", tags: ["Shipments"], security: [["ApiKeyAuth" => []]])]
    #[OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))]
    #[OA\Response(response: 200, description: "Berhasil mengambil detail")]
    #[OA\Response(response: 404, description: "Data tidak ditemukan")]
    public function show(Request $request, $id)
    {
        $user = $this->verifyJwtAndGetUser($request);
        if (!$user) {
            return $this->formatResponse('error', 'Unauthorized: Token JWT tidak valid atau tidak ditemukan.', null, 401);
        }

        $shipment = InboundShipment::find($id);
        if (!$shipment) {
            return $this->formatResponse('error', 'Kargo tidak ditemukan', null, 404);
        }
        return $this->formatResponse('success', 'Detail kargo berhasil diambil', $shipment);
    }

    #[OA\Post(path: "/api/v1/inbound-shipments", summary: "Menerima data manifest", tags: ["Shipments"], security: [["ApiKeyAuth" => []]])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(properties: [new OA\Property(property: "supplier_name", type: "string"), new OA\Property(property: "manifest_data", type: "string")]))]
    #[OA\Response(response: 201, description: "Berhasil membuat data")]
    public function store(Request $request, IaeCentralService $iaeService)
    {
        $user = $this->verifyJwtAndGetUser($request);
        if (!$user) {
            return $this->formatResponse('error', 'Unauthorized: Token JWT tidak valid atau tidak ditemukan.', null, 401);
        }

        $validated = $request->validate([
            'supplier_name' => 'required|string',
            'manifest_data' => 'required|string',
        ]);

        $validated['tracking_number'] = 'TRK-' . strtoupper(uniqid());
        $validated['status'] = 'on_the_way';
        $validated['estimated_arrival'] = now()->addDays(3);
        $validated['current_position'] = 'Menunggu diberangkatkan';

        $shipment = InboundShipment::create($validated);

        $logData = [
            'shipment_id' => $shipment->id,
            'tracking_number' => $shipment->tracking_number,
            'supplier_name' => $shipment->supplier_name,
            'manifest_data' => $shipment->manifest_data
        ];

        $auditResponse = $iaeService->sendAudit('InboundShipmentCreated', $logData);

        preg_match('/<iae:ReceiptNumber>(.*?)<\/iae:ReceiptNumber>/', $auditResponse, $matches);
        $receiptNumber = $matches[1] ?? null;

        if ($receiptNumber) {
            $shipment->legacy_receipt_number = $receiptNumber;
            $shipment->save();
        }

        $eventPayload = [
            'event_name' => 'InboundShipmentCreated',
            'service_name' => 'Expedition-Service',
            'api_version' => 'v1',
            'occurred_at' => now()->toIso8601String(),
            'sender' => 'TEAM-03',
            'shipment_data' => $shipment->toArray()
        ];

        $rabbitResponse = $iaeService->publishEvent([
            'routing_key' => 'shipment.created',
            'message' => $eventPayload
        ]);
    
        $responseData = [
            'shipment' => $shipment,
            'legacy_receipt' => $receiptNumber,
            'rabbitmq_status' => $rabbitResponse
        ];

        return $this->formatResponse('success', 'Data manifest diterima, jadwal dan resi berhasil diterbitkan', $responseData, 201);
    }
}