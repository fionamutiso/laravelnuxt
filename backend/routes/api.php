<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\TaskController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Make tasks API public
Route::apiResource('tasks', TaskController::class);
// Make categories API public
Route::apiResource('categories', \App\Http\Controllers\CategoryController::class);
// Make feedback API public
Route::apiResource('feedback', \App\Http\Controllers\FeedbackController::class);

Route::middleware('auth:sanctum')->group(function () {
    // Other protected routes can go here
});