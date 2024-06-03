<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

/**
 * Post API routes
 *
 * These routes are responsible for handling CRUD operations on posts.
 */

// Retrieve a list of all posts
Route::get('posts', [PostController::class, 'index']);

// Retrieve a specific post by ID
Route::get('posts/{id}', [PostController::class, 'show']);

// Create a new post
Route::post('posts', [PostController::class, 'store']);

// Delete a post by ID
Route::delete('posts/{id}', [PostController::class, 'destroy']);

/**
 * Authentication API routes
 *
 * These routes are responsible for handling user authentication.
 */

// Display the login page (if applicable)
Route::get('login', [AuthController::class, 'login_get']);

// Handle user login via POST request
Route::post('login', [AuthController::class, 'login_post']);

// Handle user logout
Route::get('logout', [AuthController::class, 'logout']);

// Refresh the JWT token
Route::get('refresh', [AuthController::class, 'refresh']);

// Handle user registration
Route::post('register', [AuthController::class, 'register']);

// Retrieve the authenticated user's details
Route::get('me', [AuthController::class, 'me']);
