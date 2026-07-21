<?php

use Illuminate\Support\Facades\Route;
use Modules\Drive\Http\Controllers\DashboardController;
use Modules\Drive\Http\Controllers\FileController;
use Modules\Drive\Http\Controllers\FolderController;
use Modules\Drive\Http\Controllers\TrashController;

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('folders')->name('folders.')->group(function () {
        Route::post('/', [FolderController::class, 'store'])
            ->name('store')
            ->middleware('can:create,Modules\Drive\Models\Folder');
        Route::get('/{folder}', [FolderController::class, 'show'])
            ->name('show')
            ->middleware('can:view,folder');
        Route::patch('/{folder}', [FolderController::class, 'update'])
            ->name('update')
            ->middleware('can:update,folder');
        Route::delete('/{folder}', [FolderController::class, 'destroy'])
            ->name('destroy')
            ->middleware('can:delete,folder');
    });

    Route::prefix('files')->name('files.')->group(function () {
        Route::post('/', [FileController::class, 'store'])
            ->name('store')
            ->middleware('can:create,Modules\Drive\Models\File');
        Route::get('/{file}/download', [FileController::class, 'show'])
            ->name('download')
            ->middleware('can:download,file');
        Route::delete('/{file}', [FileController::class, 'destroy'])
            ->name('destroy')
            ->middleware('can:delete,file');
    });

    Route::prefix('trash')->name('trash.')->group(function () {
        Route::get('/', [TrashController::class, 'index'])->name('index');
        Route::patch('/files/{file}/restore', [TrashController::class, 'restoreFile'])
            ->name('files.restore')
            ->middleware('can:restore,file');
        Route::delete('/files/{file}', [TrashController::class, 'destroyFile'])
            ->name('files.destroy')
            ->middleware('can:forceDelete,file');
        Route::patch('/folders/{folder}/restore', [TrashController::class, 'restoreFolder'])
            ->name('folders.restore')
            ->middleware('can:restore,folder');
        Route::delete('/folders/{folder}', [TrashController::class, 'destroyFolder'])
            ->name('folders.destroy')
            ->middleware('can:forceDelete,folder');
    });
});
