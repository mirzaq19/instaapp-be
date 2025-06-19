<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::middleware('auth:sanctum')->get('me', [AuthController::class, 'me']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('posts')->group(function () {
            Route::post('/', [PostController::class, 'store']);
        });
    });
});
