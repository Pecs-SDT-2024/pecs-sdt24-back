<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

// Post API routes
Route::get('v1/posts', [PostController::class, 'index']);
Route::get('v1/posts/{id}', [PostController::class, 'show']);
Route::post('v1/posts', [PostController::class, 'store']);
Route::delete('v1/posts/{id}', [PostController::class, 'destroy']);

Route::get('v1/login', [AuthController::class, 'login_get']);
Route::post('v1/login', [AuthController::class, 'login_post']);
Route::get('v1/logout', [AuthController::class, 'logout']);
Route::get('v1/refresh', [AuthController::class, 'refresh']);
Route::post('v1/register', [AuthController::class, 'register']);
Route::get('v1/me', [AuthController::class, 'me']);
