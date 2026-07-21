<?php

use Illuminate\Support\Facades\Route;
use Modules\Sharing\Http\Controllers\ShareController;

Route::middleware('auth')->prefix('shared')->name('share.')->group(function () {
    Route::get('/', [ShareController::class, 'index'])->name('index');
    Route::post('/', [ShareController::class, 'store'])->name('store');
    Route::patch('/{shared}', [ShareController::class, 'update'])
        ->name('update')
        ->middleware('can:manage,shared');
    Route::delete('/{shared}', [ShareController::class, 'destroy'])
        ->name('destroy')
        ->middleware('can:manage,shared');
});
