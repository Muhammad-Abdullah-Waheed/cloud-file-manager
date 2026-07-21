<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\HomeController;
use Modules\Core\Http\Controllers\LanguageController;
use Modules\Core\Http\Controllers\NotificationController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/language/{locale}', [LanguageController::class, 'switch'])
    ->name('language.switch')
    ->whereIn('locale', ['en', 'ar']);

Route::middleware('auth')->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::patch('/read-all', [NotificationController::class, 'markAllAsRead'])->name('readAll');
    Route::patch('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
});
