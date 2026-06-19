<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ProcurementController;

/*
|--------------------------------------------------------------------------
| API Routes - Service B (Procurement / Pengadaan Bahan Baku)
|--------------------------------------------------------------------------
|
| Endpoints:
|   GET    /api/v1/procurements       → Daftar seluruh Purchase Order
|   GET    /api/v1/procurements/{id}  → Detail satu Purchase Order
|   POST   /api/v1/procurements       → Buat Purchase Order baru
|
| Semua endpoint dilindungi oleh middleware api.key (X-IAE-KEY header)
|
*/

Route::prefix('v1')->middleware('api.key')->group(function () {
    Route::get('/procurements', [ProcurementController::class, 'index']);
    Route::get('/procurements/{id}', [ProcurementController::class, 'show']);
    Route::post('/procurements', [ProcurementController::class, 'store']);
    Route::post('/orders/complete', [ProcurementController::class, 'completeOrder']);
});
