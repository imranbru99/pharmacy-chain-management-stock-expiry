<?php

declare(strict_types=1);

use App\Http\Controllers\Portal\CustomerPortalController;
use App\Http\Controllers\Portal\RiderPortalController;
use App\Http\Controllers\Portal\SupplierPortalController;
use Illuminate\Support\Facades\Route;

Route::prefix('portal')->group(function (): void {
    Route::prefix('rider')->name('portal.rider.')->group(function (): void {
        Route::get('/', [RiderPortalController::class, 'index'])->name('index');
        Route::get('/deliveries', [RiderPortalController::class, 'deliveries'])->name('deliveries');
    });

    Route::prefix('supplier')->name('portal.supplier.')->group(function (): void {
        Route::get('/', [SupplierPortalController::class, 'index'])->name('index');
        Route::get('/orders', [SupplierPortalController::class, 'orders'])->name('orders');
    });

    Route::prefix('customer')->name('portal.customer.')->group(function (): void {
        Route::get('/', [CustomerPortalController::class, 'index'])->name('index');
        Route::get('/orders', [CustomerPortalController::class, 'orders'])->name('orders');
    });
});
