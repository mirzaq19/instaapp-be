<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
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
            Route::middleware('optional_auth')->withoutMiddleware('auth:sanctum')->group(function () {
                Route::get('/', [PostController::class, 'index']);
                Route::get('{id}', [PostController::class, 'show']);
            });
            Route::post('/', [PostController::class, 'store']);
            Route::delete('{id}', [PostController::class, 'destroy']);

            Route::prefix('{postId}/comments')->group(function () {
                Route::get('/', [CommentController::class, 'index'])->withoutMiddleware('auth:sanctum');
                Route::post('/', [CommentController::class, 'store']);
                Route::delete('{commentId}', [CommentController::class, 'destroy']);
            });

            Route::post('{postId}/like', [PostController::class, 'toggleLike']);
        });
    });
});
