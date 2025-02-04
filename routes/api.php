<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DonationController;
use App\Http\Controllers\Api\V1\ProjectController;
use Illuminate\Support\Facades\Route;

// Rotas públicas
Route::prefix('v1')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
});

// Rotas autenticadas
Route::prefix('v1')->middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::apiResource('donations', DonationController::class);
    Route::apiResource('projects', ProjectController::class);
});
