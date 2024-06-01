<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

// Post API routes
Route::get('posts', [PostController::class, 'index']);
Route::get('posts/{id}', [PostController::class, 'show']);
Route::post('posts', [PostController::class, 'store']);
Route::delete('posts/{id}', [PostController::class, 'destroy']);
