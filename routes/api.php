<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\{
    UserController,
    CategoryController,
    TeamController
};
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/me', [AuthController::class, 'me'])->name('me');

    Route::apiResource('/users', UserController::class);

    Route::apiResource('/categories', CategoryController::class);

    Route::apiResource('/teams', TeamController::class);
    Route::get('/teams/{id}/coworkers', [TeamController::class, 'showCoworker']);
    Route::post('/teams/{id}/coworkers', [TeamController::class, 'storeCoworker']);
    Route::delete('/teams/{id}/coworkers', [TeamController::class, 'destroyCoworker']);
});

