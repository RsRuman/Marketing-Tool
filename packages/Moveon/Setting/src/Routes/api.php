<?php

use Illuminate\Support\Facades\Route;
use Moveon\Setting\Http\Controllers\AccountController;
use Moveon\Setting\Http\Controllers\EventController;
use Moveon\Setting\Http\Controllers\ImportController;
use Moveon\Setting\Http\Controllers\IntegrationController;
use Moveon\Setting\Http\Controllers\UserSettingController;


Route::group(['prefix' => 'v1', 'middleware' => ['auth:api']], function () {
    # Product Route
    Route::group(['prefix' => 'settings'], function () {
        # Account setting
        Route::get('/account', [AccountController::class, 'index']);
        Route::put('/account', [AccountController::class, 'update']);
        # User setting
        Route::get('/user', [UserSettingController::class, 'index']);
        Route::put('/user', [UserSettingController::class, 'update']);
        Route::post('/user/password-reset/send-otp', [UserSettingController::class, 'sendOtp']);
        Route::post('/user/password-reset', [UserSettingController::class, 'resetPassword']);
        # Platform integration setting
        Route::get('/integrations', [IntegrationController::class, 'index']);
        Route::get('/integrations/{slug}', [IntegrationController::class, 'show']);
        # Import data
        Route::post('/integrations/{slug}/import', [ImportController::class, 'import']);
    });
});

# Event endpoint
Route::post('event', [EventController::class, 'store']);
