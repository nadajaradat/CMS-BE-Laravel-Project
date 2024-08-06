<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebsiteController;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// profile management routes
Route::controller(UserController::class)->group(function () {
    Route::get('/user/{user}/profile', 'indexProfile');
    Route::put('/user/{user}/profile', 'updateProfile')->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // user management routes
    Route::middleware(['role:admin'])->group(function () {
        Route::controller(UserController::class)->group(function () {
            Route::get('/user', 'index');
            Route::post('/user', 'store');
            Route::get('/user/{user}', 'show');
            Route::put('/user/{user}', 'update');
            Route::delete('/user/{user}', 'destroy');
        });
    });
});
