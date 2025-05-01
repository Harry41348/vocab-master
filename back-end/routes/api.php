<?php

use App\Http\Controllers\Api\HealthCheckController;
use App\Http\Controllers\Api\PackController;
use App\Http\Controllers\Api\UserAuthController;
use Illuminate\Support\Facades\Route;

/**
 * This is where API routes are defined to be used by external applications.
 */
Route::name('api.')->group(function () {
    /**
     * Unprotected API routes
     */
    Route::get('/health-check', [HealthCheckController::class, 'check'])->name('health-check');

    Route::post('register', [UserAuthController::class, 'register'])->name('register');
    Route::post('login', [UserAuthController::class, 'login'])->name('login');

    // Pack routes
    Route::resource('packs', PackController::class)
        ->only(['index', 'show'])
        ->names('packs');

    /**
     * Protected API routes
     */
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('logout', [UserAuthController::class, 'logout'])->name('logout');

        Route::resource('packs', PackController::class)
            ->only(['store', 'update', 'destroy'])
            ->names('packs');
    });
});
