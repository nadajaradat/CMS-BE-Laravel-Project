<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\PatientController;
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

        // department management routes
        Route::controller(DepartmentController::class)->group(function () {
            Route::get('/department', 'index');
            Route::post('/department', 'store');
            Route::get('/department/{department}', 'show');
            Route::put('/department/{department}', 'update');
            Route::delete('/department/{department}', 'destroy');
        });

        // doctor management routes
        Route::controller(DoctorController::class)->group(function () {
            Route::get('/doctor', 'index');
            Route::post('/doctor', 'store');
            Route::get('/doctor/{doctor}', 'show');
            Route::put('/doctor/{doctor}', 'update');
        });
    });

    Route::middleware(['role:admin,doctor'])->group(function () {
        // patient management routes
        Route::controller(PatientController::class)->group(function () {
            Route::get('/patient', 'index');
            Route::post('/patient', 'store');
            Route::get('/patient/{patient}', 'show');
            Route::put('/patient/{patient}', 'update');
        });
    });
});
