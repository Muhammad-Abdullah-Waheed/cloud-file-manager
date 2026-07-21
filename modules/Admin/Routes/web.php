<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AdminController;
use Modules\Admin\Http\Controllers\DeleteRequestController;

Route::middleware(['auth', 'permission:view-all-files'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [AdminController::class, 'index'])->name('index');
            Route::get('/{user}', [AdminController::class, 'showUser'])->name('show');
            Route::get('/{user}/folders/{folder}', [AdminController::class, 'showFolder'])->name('folders.show');
            Route::get('/{user}/files/{file}/download', [AdminController::class, 'downloadFile'])->name('files.download');

            Route::middleware('permission:delete-any-file')->group(function () {
                Route::delete('/{user}/files/{file}', [AdminController::class, 'destroyFile'])->name('files.destroy');
                Route::delete('/{user}/folders/{folder}', [AdminController::class, 'destroyFolder'])->name('folders.destroy');
            });
        });

        Route::prefix('delete-requests')->name('delete-requests.')->group(function () {
            Route::get('/', [DeleteRequestController::class, 'index'])->name('index');
            Route::post('/', [DeleteRequestController::class, 'store'])->name('store');

            Route::middleware('permission:delete-any-file')->group(function () {
                Route::patch('/{deleteRequest}/approve', [DeleteRequestController::class, 'approve'])->name('approve');
                Route::patch('/{deleteRequest}/reject', [DeleteRequestController::class, 'reject'])->name('reject');
            });
        });
    });
