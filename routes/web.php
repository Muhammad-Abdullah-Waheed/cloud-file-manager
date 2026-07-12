<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LoginUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\TrashController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/language/{locale}', [LanguageController::class, 'switch'])
    ->name('language.switch')
    ->whereIn('locale', ['en', 'ar']);

// Guest only
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisterUserController::class, 'store']);
    Route::get('/login', [LoginUserController::class, 'create'])->name('login');
    Route::post('/login', [LoginUserController::class, 'login']);
});

// Auth only
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginUserController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/folders', [FolderController::class, 'store'])->name('folders.store');
    Route::patch('/folders/{folder}', [FolderController::class, 'update'])->name('folders.update');
    Route::delete('/folders/{folder}', [FolderController::class, 'destroy'])->name('folders.destroy');
    
    Route::post('/files', [FileController::class, 'store'])->name('files.store');
    Route::get('/files/{file}/download', [FileController::class, 'show'])->name('files.download');
    Route::delete('/files/{file}', [FileController::class, 'destroy'])->name('files.destroy');

    Route::get('/trash', [TrashController::class, 'index'])->name('trash');
    Route::patch('/trash/files/{file}/restore', [TrashController::class, 'restoreFile'])->name('trash.files.restore');
    Route::patch('/trash/folders/{folder}/restore', [TrashController::class, 'restoreFolder'])->name('trash.folders.restore');
    Route::delete('/trash/files/{file}', [TrashController::class, 'destroyFile'])->name('trash.files.destroy');
    Route::delete('/trash/folders/{folder}', [TrashController::class, 'destroyFolder'])->name('trash.folders.destroy');
});