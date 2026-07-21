<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\LoginUserController;
use Modules\Auth\Http\Controllers\RegisterUserController;

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisterUserController::class, 'store']);
    Route::get('/login', [LoginUserController::class, 'create'])->name('login');
    Route::post('/login', [LoginUserController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginUserController::class, 'logout'])->name('logout');
});
