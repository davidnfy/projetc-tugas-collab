<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DailyTodoController;
use App\Http\Controllers\ImportantTodoController;
use App\Http\Controllers\UserTodoController;
use App\Http\Controllers\UserTodoCategoryController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/profile', function (Request $request) {
        return response()->json($request->user());
    });

    // Route::controller(UserController::class)->group(function () {
    //     Route::get('/users', 'getAllUser');         
    //     Route::get('/users/{id}', 'getUserById');   
    //     Route::put('/users/{id}', 'update');        
    //     Route::delete('/users/{id}', 'delete');     
    // });

    Route::controller(UserTodoCategoryController::class)->group(function () {
    Route::get('/categories', 'index');
    Route::post('/categories', 'store');
    Route::delete('/categories/{id}', 'destroy');
});

    Route::controller(DailyTodoController::class)->prefix('daily')->group(function () {
        Route::get('/', 'index');          
        Route::post('/', 'store');        
        Route::get('/{id}', 'show');       
        Route::put('/{id}', 'update');    
        Route::delete('/{id}', 'destroy'); 
    });

    Route::controller(ImportantTodoController::class)->prefix('important')->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });

    Route::controller(UserTodoController::class)->prefix('user')->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });

    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'endpoint ga ada'
    ], 404);
});
