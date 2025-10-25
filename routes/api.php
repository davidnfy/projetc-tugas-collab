<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', function (Request $request) {
        return $request->user();
    });
    Route::get('/get', [UserController::class, 'getAllUser']);
    Route::put('/update/{id}', [UserController::class, 'update']);
    Route::delete('/delete/{id}', [UserController::class, 'delete']);
    Route::get('/get/{id}', [UserController::class, 'getUserById']);

    Route::post('/logout', [AuthController::class, 'logout']);
});

