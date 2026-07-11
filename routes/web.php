<?php

use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::get('/register', function () {
    return 'Hello World';
});

Route::post('/register', function () {
    return 'Hello World';
});
