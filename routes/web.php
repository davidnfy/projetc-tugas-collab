<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DailyTodoController;
use App\Http\Controllers\ImportantTodoController;

Route::view('/', 'welcome');

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login');
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout')->name('logout');
});

Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');

Route::middleware('auth')->prefix('dashboard')->group(function () {

    Route::view('/', 'dashboard')->name('dashboard');

    Route::controller(DailyTodoController::class)->prefix('daily')->name('daily.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::patch('{id}/toggle', 'toggle')->name('toggle');
        Route::delete('{id}', 'destroy')->name('destroy');
    });

    Route::controller(ImportantTodoController::class)->prefix('important')->name('important.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::patch('{id}/toggle', 'toggle')->name('toggle');
        Route::delete('{id}', 'destroy')->name('destroy');
    });
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/auth/google', 'redirectToGoogle');
    Route::get('/auth/google/callback', 'handleGoogleCallback');
});
