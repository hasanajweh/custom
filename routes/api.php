<?php

use App\Http\Controllers\Api\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/switch-context', [AuthController::class, 'switchContext']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/logout-all', [AuthController::class, 'logoutAll']);
    
    // Files management
    Route::prefix('files')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\FilesController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\FilesController::class, 'store']);
        Route::get('/subjects-grades', [\App\Http\Controllers\Api\FilesController::class, 'getSubjectsGrades']);
        Route::get('/{id}', [\App\Http\Controllers\Api\FilesController::class, 'show']);
        Route::get('/{id}/download', [\App\Http\Controllers\Api\FilesController::class, 'download']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\FilesController::class, 'destroy']);
    });

    // Profile management
    Route::prefix('profile')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\ProfileController::class, 'show']);
        Route::put('/', [\App\Http\Controllers\Api\ProfileController::class, 'update']);
        Route::put('/password', [\App\Http\Controllers\Api\ProfileController::class, 'updatePassword']);
    });

    // Add your tenant-aware API routes here
    // Example:
    // Route::prefix('{network}/{school}')->group(function () {
    //     Route::get('/dashboard', [DashboardController::class, 'index']);
    // });
});
