<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\SaleController;
use App\Http\Controllers\Api\V1\StockController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('auth:sanctum')->group(function (): void {
    Route::get('products', [ProductController::class, 'index']);
    Route::get('branches/{branch}/stock', [StockController::class, 'byBranch']);
    Route::post('sales', [SaleController::class, 'store']);
});
