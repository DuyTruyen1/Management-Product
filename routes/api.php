<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\UserApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('login', [UserApiController::class, 'login']);
Route::post('register', [UserApiController::class, 'register']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
  // Auth routes
  Route::post('logout', [UserApiController::class, 'logout']);

  // User profile routes
  Route::get('profile', [UserApiController::class, 'profile']);
  Route::put('profile', [UserApiController::class, 'updateProfile']);

  // Admin only routes
  Route::middleware('role:admin')->group(function () {
    Route::apiResource('users', UserApiController::class);
  });

  // User management routes
  Route::apiResource('categories', CategoryApiController::class);
  Route::apiResource('products', ProductApiController::class);
});
