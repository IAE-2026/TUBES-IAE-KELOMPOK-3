<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\InboundShipmentController;
Route::prefix('v1')->group(function () {
    Route::get('/inbound-shipments', [InboundShipmentController::class, 'index']);
    Route::get('/inbound-shipments/{id}', [InboundShipmentController::class, 'show']);
    Route::post('/inbound-shipments', [InboundShipmentController::class, 'store']);
});