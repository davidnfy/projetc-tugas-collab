<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DailyTodoController;
use App\Http\Controllers\ImportantTodoController;
use App\Http\Controllers\UserTodoController;
use App\Http\Controllers\UserTodoCategoryController;

Route::view('/', 'welcome');

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login');
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout')->name('logout');
});

Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');

Route::middleware('auth')->group(function () {

    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::controller(DailyTodoController::class)
        ->prefix('daily')
        ->name('daily.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::patch('{id}', 'update')->name('update');
            Route::patch('{id}/toggle', 'toggle')->name('toggle');
            Route::delete('{id}', 'destroy')->name('destroy');
        });

    Route::controller(ImportantTodoController::class)
        ->prefix('important')
        ->name('important.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::patch('{id}', 'update')->name('update');
            Route::patch('{id}/toggle', 'toggle')->name('toggle');
            Route::delete('{id}', 'destroy')->name('destroy');
        });

    Route::controller(UserTodoCategoryController::class)
        ->prefix('categories')
        ->name('category.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('{id}', 'show')->name('show');
            Route::post('/', 'store')->name('store');
            Route::patch('{id}', 'update')->name('update');
            Route::delete('{id}', 'destroy')->name('destroy');
        });

    Route::controller(UserTodoController::class)
        ->prefix('todos')
        ->name('user.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::patch('{id}', 'update')->name('update');
            Route::patch('{id}/toggle', 'toggle')->name('toggle');
            Route::delete('{id}', 'destroy')->name('destroy');
        });
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/auth/google', 'redirectToGoogle');
    Route::get('/auth/google/callback', 'handleGoogleCallback');
});
