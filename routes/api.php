<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use App\Http\Controllers\SectionController;
use \App\Http\Controllers\UserController;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('book', BookController::class);
    Route::apiResource('section', SectionController::class);
    Route::post('store-child-section', [SectionController::class, 'createChildSection']);
    Route::get('fetch-users',[UserController::class,'fetchUsers']);
    Route::get('switch-user-role/{id}',[UserController::class,'switchUserRole']);

    Route::post('logout', [AuthController::class, 'logout']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
