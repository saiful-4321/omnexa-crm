<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Tenant\Http\Controllers\TenantController;

Route::group(['prefix' => 'dashboard/tenants', 'middleware' => ['web', 'auth']], function () {
    Route::get('/', [TenantController::class, 'index'])->name('dashboard.tenants.index');
    Route::get('/create', [TenantController::class, 'create'])->name('dashboard.tenants.create');
    Route::post('/', [TenantController::class, 'store'])->name('dashboard.tenants.store');
    
    Route::get('/{id}/edit', [TenantController::class, 'edit'])->name('dashboard.tenants.edit');
    Route::put('/{id}', [TenantController::class, 'update'])->name('dashboard.tenants.update');

    Route::get('/{id}/branding', [TenantController::class, 'branding'])->name('dashboard.tenants.branding');
    Route::post('/{id}/branding', [TenantController::class, 'updateBranding'])->name('dashboard.tenants.branding.update');
});
