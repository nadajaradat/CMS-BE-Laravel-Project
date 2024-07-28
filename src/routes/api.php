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


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::controller(UserController::class)->group(function () {
        Route::get('/user', 'index');
        Route::post('/user', 'store');
        Route::get('/user/{user}', 'show');
        Route::put('/user/{user}', 'update');
        Route::delete('/user/{user}', 'destroy');
    });

    Route::controller(EducationController::class)->group(function () {
        Route::post('/user/{user}/education', 'store');
        Route::get('/user/{user}/education', 'index');
        Route::get('/education/{education}', 'show');
        Route::put('/education/{education}', 'update');
        Route::delete('/education/{education}', 'destroy');
    });

    Route::controller(SkillController::class)->group(function () {
        Route::post('/user/{user}/skill', 'store');
        Route::get('/user/{user}/skill', 'index');
        Route::get('/skill/{skill}', 'show');
        Route::put('/skill/{skill}', 'update');
        Route::delete('/skill/{skill}', 'destroy');
    });

    Route::controller(ExperienceController::class)->group(function () {
        Route::post('/user/{user}/experience', 'store');
        Route::get('/user/{user}/experience', 'index');
        Route::get('/experience/{experience}', 'show');
        Route::put('/experience/{experience}', 'update');
        Route::delete('/experience/{experience}', 'destroy');
    });

    Route::controller(WebsiteController::class)->group(function () {
        Route::post('/user/{user}/website', 'store');
        Route::get('/user/{user}/website', 'index');
        Route::get('/website/{website}', 'show');
        Route::put('/website/{website}', 'update');
        Route::delete('/website/{website}', 'destroy');
    });

    
});
