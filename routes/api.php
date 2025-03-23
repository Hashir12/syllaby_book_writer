<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use App\Http\Controllers\SectionController;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('book', BookController::class);
    Route::apiResource('section', SectionController::class);
    Route::post('store-child-section', [SectionController::class, 'createChildSection']);

    Route::post('logout', [AuthController::class, 'logout']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
