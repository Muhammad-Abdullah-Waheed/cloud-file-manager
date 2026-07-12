<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LoginUserController;


Route::get('/', [HomeController::class,'index'])->name('home');
Route::post('/language/{locale}', [LanguageController::class,'switch'])->name('language.switch');

Route::get('/register', [RegisterUserController::class,'create'])->name('register');
Route::post('/register', [RegisterUserController::class,'store']);
Route::get('/login', [LoginUserController::class,'create'])->name('login');
Route::post('/login', [LoginUserController::class,'login']);
Route::post('/logout', [LoginUserController::class,'logout'])->name('logout');