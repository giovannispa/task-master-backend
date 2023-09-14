<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\{
    UserController,
    CategoryController,
    TeamController,
    ProjectController
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

    Route::apiResource('/projects', ProjectController::class);
    Route::get('/projects/{id}/teams', [ProjectController::class, 'showTeam']);
    Route::post('/projects/{id}/teams', [ProjectController::class, 'storeTeam']);
    Route::delete('/projects/{id}/teams', [ProjectController::class, 'destroyTeam']);
});

