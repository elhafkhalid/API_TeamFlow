<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WorkspaceController;
use App\Http\Controllers\Api\ProjectController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/workspaces', [WorkspaceController::class, 'store']);
    Route::get('/workspaces', [WorkspaceController::class, 'index']);

});

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/workspaces/{workspace}/projects', [ProjectController::class, 'store']);
    Route::get('/projects/{id}', [ProjectController::class, 'show']);
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);
});