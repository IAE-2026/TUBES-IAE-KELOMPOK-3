<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Procurement;
use App\Models\ProcurementItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Procurement",
    type: "object",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "po_number", type: "string", example: "PO-20260515-001"),
        new OA\Property(property: "supplier_name", type: "string", example: "PT. Chip Elektronik Indonesia"),
        new OA\Property(property: "supplier_contact", type: "string", example: "sales@chipelektronik.co.id"),
        new OA\Property(property: "order_date", type: "string", format: "date", example: "2026-05-15"),
        new OA\Property(property: "expected_delivery_date", type: "string", format: "date", example: "2026-05-25"),
        new OA\Property(property: "status", type: "string", enum: ["draft", "submitted", "approved", "in_progress", "shipped", "delivered", "cancelled"], example: "submitted"),
        new OA\Property(property: "total_amount", type: "number", format: "float", example: 15000000.00),
        new OA\Property(property: "currency", type: "string", example: "IDR"),
        new OA\Property(property: "notes", type: "string", example: "Urgent untuk lini produksi batch #45"),
        new OA\Property(property: "created_by", type: "string", example: "Admin Procurement"),
        new OA\Property(property: "created_at", type: "string", format: "datetime"),
        new OA\Property(property: "updated_at", type: "string", format: "datetime"),
        new OA\Property(
            property: "items",
            type: "array",
            items: new OA\Items(ref: "#/components/schemas/ProcurementItem")
        )
    ]
)]
#[OA\Schema(
    schema: "ProcurementItem",
    type: "object",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "procurement_id", type: "integer", example: 1),
        new OA\Property(property: "component_name", type: "string", example: "IC Mikrokontroler ATmega328P"),
        new OA\Property(property: "part_number", type: "string", example: "ATMEGA328P-PU"),
        new OA\Property(property: "quantity", type: "integer", example: 500),
        new OA\Property(property: "unit", type: "string", example: "pcs"),
        new OA\Property(property: "unit_price", type: "number", format: "float", example: 25000.00),
        new OA\Property(property: "subtotal", type: "number", format: "float", example: 12500000.00),
        new OA\Property(property: "created_at", type: "string", format: "datetime"),
        new OA\Property(property: "updated_at", type: "string", format: "datetime")
    ]
)]
class ProcurementController extends Controller
{
    #[OA\Get(
        path: "/procurements",
        operationId: "getAllProcurements",
        summary: "Mengambil daftar seluruh riwayat Purchase Order",
        security: [["X-IAE-KEY" => []]],
        tags: ["Procurements"],
        description: "Endpoint Collection: Menampilkan daftar seluruh Purchase Order (PO) yang pernah diajukan oleh pabrik ke berbagai supplier.",
        responses: [
            new OA\Response(
                response: 200,
                description: "Data retrieved successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "message", type: "string", example: "Data retrieved successfully"),
                        new OA\Property(
                            property: "data",
                            type: "array",
                            items: new OA\Items(ref: "#/components/schemas/Procurement")
                        ),
                        new OA\Property(
                            property: "meta",
                            type: "object",
                            properties: [
                                new OA\Property(property: "service_name", type: "string", example: "Procurement-Service"),
                                new OA\Property(property: "api_version", type: "string", example: "v1")
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized - API Key tidak valid",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "error"),
                        new OA\Property(property: "message", type: "string", example: "Unauthorized. API Key (X-IAE-KEY) tidak valid atau tidak ditemukan."),
                        new OA\Property(property: "errors", type: "string", nullable: true, example: null)
                    ]
                )
            )
        ]
    )]
    public function index(): JsonResponse
    {
        $procurements = Procurement::with('items')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully',
            'data' => $procurements,
            'meta' => [
                'service_name' => 'Procurement-Service',
                'api_version' => 'v1',
            ],
        ], 200);
    }

    #[OA\Get(
        path: "/procurements/{id}",
        operationId: "getProcurementById",
        summary: "Mengambil detail kelengkapan dari satu Purchase Order",
        security: [["X-IAE-KEY" => []]],
        tags: ["Procurements"],
        description: "Endpoint Resource: Menampilkan rincian satu pesanan Purchase Order untuk mengetahui kelengkapannya (daftar part number komponen yang dipesan dan total harga).",
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID Purchase Order",
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Data retrieved successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "message", type: "string", example: "Data retrieved successfully"),
                        new OA\Property(property: "data", ref: "#/components/schemas/Procurement"),
                        new OA\Property(
                            property: "meta",
                            type: "object",
                            properties: [
                                new OA\Property(property: "service_name", type: "string", example: "Procurement-Service"),
                                new OA\Property(property: "api_version", type: "string", example: "v1")
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Purchase Order tidak ditemukan",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "error"),
                        new OA\Property(property: "message", type: "string", example: "Purchase Order not found"),
                        new OA\Property(property: "errors", type: "string", nullable: true, example: null)
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized"
            )
        ]
    )]
    public function show(int $id): JsonResponse
    {
        $procurement = Procurement::with('items')->find($id);

        if (!$procurement) {
            return response()->json([
                'status' => 'error',
                'message' => 'Purchase Order not found',
                'errors' => null,
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully',
            'data' => $procurement,
            'meta' => [
                'service_name' => 'Procurement-Service',
                'api_version' => 'v1',
            ],
        ], 200);
    }

    #[OA\Post(
        path: "/procurements",
        operationId: "createProcurement",
        summary: "Membuat dokumen Purchase Order (PO) baru",
        security: [["X-IAE-KEY" => []]],
        tags: ["Procurements"],
        description: "Endpoint Action: Membuat Purchase Order baru yang akan dikirimkan ke pihak supplier komponen elektronik. Termasuk daftar item/komponen yang dipesan.",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["supplier_name", "order_date", "items"],
                properties: [
                    new OA\Property(property: "supplier_name", type: "string", example: "PT. Chip Elektronik Indonesia"),
                    new OA\Property(property: "supplier_contact", type: "string", example: "sales@chipelektronik.co.id"),
                    new OA\Property(property: "order_date", type: "string", format: "date", example: "2026-05-15"),
                    new OA\Property(property: "expected_delivery_date", type: "string", format: "date", example: "2026-05-25"),
                    new OA\Property(property: "status", type: "string", enum: ["draft", "submitted", "approved", "in_progress", "shipped", "delivered", "cancelled"], example: "submitted"),
                    new OA\Property(property: "currency", type: "string", example: "IDR"),
                    new OA\Property(property: "notes", type: "string", example: "Urgent untuk lini produksi batch #45"),
                    new OA\Property(property: "created_by", type: "string", example: "Admin Procurement"),
                    new OA\Property(
                        property: "items",
                        type: "array",
                        items: new OA\Items(
                            required: ["component_name", "part_number", "quantity", "unit_price"],
                            properties: [
                                new OA\Property(property: "component_name", type: "string", example: "IC Mikrokontroler ATmega328P"),
                                new OA\Property(property: "part_number", type: "string", example: "ATMEGA328P-PU"),
                                new OA\Property(property: "quantity", type: "integer", example: 500),
                                new OA\Property(property: "unit", type: "string", example: "pcs"),
                                new OA\Property(property: "unit_price", type: "number", format: "float", example: 25000.00)
                            ]
                        )
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Purchase Order berhasil dibuat",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "message", type: "string", example: "Purchase Order created successfully"),
                        new OA\Property(property: "data", ref: "#/components/schemas/Procurement"),
                        new OA\Property(
                            property: "meta",
                            type: "object",
                            properties: [
                                new OA\Property(property: "service_name", type: "string", example: "Procurement-Service"),
                                new OA\Property(property: "api_version", type: "string", example: "v1")
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Validation Error",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "error"),
                        new OA\Property(property: "message", type: "string", example: "Validation failed"),
                        new OA\Property(property: "errors", type: "object")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized"
            )
        ]
    )]
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'supplier_name' => 'required|string|max:255',
            'supplier_contact' => 'nullable|string|max:255',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after_or_equal:order_date',
            'status' => 'nullable|in:draft,submitted,approved,in_progress,shipped,delivered,cancelled',
            'currency' => 'nullable|string|max:3',
            'notes' => 'nullable|string',
            'created_by' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.component_name' => 'required|string|max:255',
            'items.*.part_number' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit' => 'nullable|string|max:50',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Generate PO Number otomatis
            $poNumber = 'PO-' . date('Ymd') . '-' . str_pad(
                (Procurement::whereDate('created_at', today())->count() + 1),
                3,
                '0',
                STR_PAD_LEFT
            );

            // Hitung total amount dari items
            $totalAmount = 0;
            $itemsData = [];
            foreach ($request->items as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];
                $totalAmount += $subtotal;
                $itemsData[] = [
                    'component_name' => $item['component_name'],
                    'part_number' => $item['part_number'],
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'] ?? 'pcs',
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $subtotal,
                ];
            }

            // Simpan Procurement
            $procurement = Procurement::create([
                'po_number' => $poNumber,
                'supplier_name' => $request->supplier_name,
                'supplier_contact' => $request->supplier_contact,
                'order_date' => $request->order_date,
                'expected_delivery_date' => $request->expected_delivery_date,
                'status' => $request->status ?? 'draft',
                'total_amount' => $totalAmount,
                'currency' => $request->currency ?? 'IDR',
                'notes' => $request->notes,
                'created_by' => $request->created_by,
            ]);

            // Simpan Items
            foreach ($itemsData as $itemData) {
                $procurement->items()->create($itemData);
            }

            DB::commit();

            // Load items untuk response
            $procurement->load('items');

            // --- INTEGRASI TUGAS 3: SOAP Audit & RabbitMQ Publish ---
            try {
                // 1. SOAP Audit (Selalu menggunakan M2M token untuk otorisasi sistem-ke-sistem)
                $soapService = app(\App\Services\SoapClientService::class);
                $receiptNumber = $soapService->auditTransaction('ProcurementCreated', $procurement->toArray());
                if ($receiptNumber) {
                    $procurement->update(['soap_receipt_number' => $receiptNumber]);
                }

                // 2. RabbitMQ Publish (Selalu menggunakan M2M token)
                $rabbitmqService = app(\App\Services\AmqpPublisherService::class);
                $rabbitmqService->publishEvent('procurement.created', $procurement->toArray());
            } catch (\Exception $auditEx) {
                // Log and ignore audit exceptions so it does not block the API response
                \Illuminate\Support\Facades\Log::error('Tugas 3 Integrations Failed: ' . $auditEx->getMessage());
            }
            // --------------------------------------------------------

            return response()->json([
                'status' => 'success',
                'message' => 'Purchase Order created successfully',
                'data' => $procurement,
                'meta' => [
                    'service_name' => 'Procurement-Service',
                    'api_version' => 'v1',
                ],
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create Purchase Order: ' . $e->getMessage(),
                'errors' => null,
            ], 500);
        }
    }

    #[OA\Post(
        path: "/orders/complete",
        operationId: "completeOrder",
        summary: "Menyelesaikan Purchase Order (PO) setelah barang diterima di gudang",
        security: [["X-IAE-KEY" => []], ["BearerAuth" => []]],
        tags: ["Procurements"],
        description: "Endpoint internal: Dipanggil oleh Service A (Inventory) ketika komponen telah sampai di gudang untuk mengupdate status PO menjadi 'delivered'.",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["purchase_order_id", "part_number", "quantity", "receipt_number"],
                properties: [
                    new OA\Property(property: "purchase_order_id", type: "string", example: "1"),
                    new OA\Property(property: "part_number", type: "string", example: "CPU-I7-12700"),
                    new OA\Property(property: "quantity", type: "integer", example: 10),
                    new OA\Property(property: "receipt_number", type: "string", example: "RC-XYZ-12345")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Purchase Order status updated to delivered",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "message", type: "string", example: "Purchase Order status updated to delivered successfully"),
                        new OA\Property(property: "data", ref: "#/components/schemas/Procurement")
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Purchase Order tidak ditemukan"
            ),
            new OA\Response(
                response: 422,
                description: "Validation Error"
            )
        ]
    )]
    public function completeOrder(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'purchase_order_id' => 'required',
            'part_number' => 'required|string',
            'quantity' => 'required|integer',
            'receipt_number' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $poId = $request->input('purchase_order_id');
        $procurement = Procurement::find($poId);

        if (!$procurement) {
            $procurement = Procurement::where('po_number', $poId)->first();
        }

        if (!$procurement) {
            return response()->json([
                'status' => 'error',
                'message' => 'Purchase Order not found',
                'errors' => null,
            ], 404);
        }

        $procurement->status = 'delivered';
        $procurement->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Purchase Order status updated to delivered successfully.',
            'data' => $procurement,
            'meta' => [
                'service_name' => 'Procurement-Service',
                'api_version' => 'v1',
            ],
        ], 200);
    }
}
