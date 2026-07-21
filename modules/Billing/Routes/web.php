<?php

use Illuminate\Support\Facades\Route;
use Modules\Billing\Http\Controllers\UpgradeRequestController;

Route::middleware('auth')->group(function () {
    Route::post('/upgrade-request', [UpgradeRequestController::class, 'store'])
        ->name('upgrade.request');
});

Route::middleware(['auth', 'permission:view-all-files', 'permission:manage-users'])
    ->prefix('admin/upgrade-requests')
    ->name('admin.upgrade-requests.')
    ->group(function () {
        Route::get('/', [UpgradeRequestController::class, 'index'])->name('index');
        Route::patch('/{upgradeRequest}/approve', [UpgradeRequestController::class, 'approve'])->name('approve');
        Route::patch('/{upgradeRequest}/reject', [UpgradeRequestController::class, 'reject'])->name('reject');
    });
