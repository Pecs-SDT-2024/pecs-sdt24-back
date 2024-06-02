<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

// Post API routes
Route::get('posts', [PostController::class, 'index']);
Route::get('posts/{id}', [PostController::class, 'show']);
Route::post('posts', [PostController::class, 'store']);
Route::delete('posts/{id}', [PostController::class, 'destroy']);

Route::get('login', [AuthController::class, 'login_get']);
Route::post('login', [AuthController::class, 'login_post']);
Route::get('logout', [AuthController::class, 'logout']);
Route::get('refresh', [AuthController::class, 'refresh']);
Route::post('register', [AuthController::class, 'register']);
Route::get('me', [AuthController::class, 'me']);
