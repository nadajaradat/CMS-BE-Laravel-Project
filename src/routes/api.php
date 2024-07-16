<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/user', 'index');
        Route::post('/user', 'store');
        Route::get('/user/{user}', 'show');
        Route::put('/user/{user}', 'update');
        Route::delete('/user/{user}', 'destroy');
    });
});