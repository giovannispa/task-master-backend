<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\{
    UserController,
    CategoryController
};
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/me', [AuthController::class, 'me'])->name('me');

    Route::apiResource('/users', UserController::class);

});

Route::apiResource('/categories', CategoryController::class);
