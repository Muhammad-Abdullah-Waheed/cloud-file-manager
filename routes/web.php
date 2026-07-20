<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeleteRequestController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LoginUserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\TrashController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/language/{locale}', [LanguageController::class, 'switch'])
    ->name('language.switch')
    ->whereIn('locale', ['en', 'ar']);

/*
|--------------------------------------------------------------------------
| Guest Routes (Authentication)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisterUserController::class, 'store']);
    Route::get('/login', [LoginUserController::class, 'create'])->name('login');
    Route::post('/login', [LoginUserController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::post('/logout', [LoginUserController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |----------------------------------------------------------------------
    | Drive: Folders (partial resource)
    |----------------------------------------------------------------------
    */
    Route::resource('folders', FolderController::class)
        ->only(['store', 'show', 'update', 'destroy']);

    /*
    |----------------------------------------------------------------------
    | Drive: Files
    |----------------------------------------------------------------------
    */
    Route::prefix('files')->name('files.')->group(function () {
        Route::post('/', [FileController::class, 'store'])->name('store');
        Route::get('/{file}/download', [FileController::class, 'show'])->name('download');
        Route::delete('/{file}', [FileController::class, 'destroy'])->name('destroy');
    });

    /*
    |----------------------------------------------------------------------
    | Trash
    |----------------------------------------------------------------------
    */
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

    /*
    |----------------------------------------------------------------------
    | Shared Files / Folders
    |----------------------------------------------------------------------
    */
    Route::prefix('shared')->name('share.')->group(function () {
        Route::get('/', [ShareController::class, 'index'])->name('index');
        Route::post('/', [ShareController::class, 'store'])->name('store');
        Route::patch('/{shared}', [ShareController::class, 'update'])
            ->name('update')
            ->middleware('can:manage,shared');
        Route::delete('/{shared}', [ShareController::class, 'destroy'])
            ->name('destroy')
            ->middleware('can:manage,shared');
    });

    /*
    |----------------------------------------------------------------------
    | Notifications
    |----------------------------------------------------------------------
    */
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::patch('/read-all', [NotificationController::class, 'markAllAsRead'])->name('readAll');
        Route::patch('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
    });

    /*
    |----------------------------------------------------------------------
    | Admin & Manager Panel
    |----------------------------------------------------------------------
    */
    Route::middleware('permission:view-all-files')
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
});
