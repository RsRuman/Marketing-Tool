<?php

use Illuminate\Support\Facades\Route;
use Moveon\EmailTemplate\Http\Controllers\EmailTemplateController;
use Moveon\EmailTemplate\Http\Controllers\EmailTemplateTagController;
use Moveon\EmailTemplate\Http\Controllers\FeatureEmailTemplateController;

Route::group(['prefix' => 'v1/', 'middleware' => ['auth:api']], function () {
    # Email template route
    Route::group(['prefix' => 'email-templates'], function () {
        Route::get('/', [EmailTemplateController::class, 'index']);
        Route::get('/{id}', [EmailTemplateController::class, 'show']);
        Route::post('/', [EmailTemplateController::class, 'store']);
        Route::put('/{id}', [EmailTemplateController::class, 'update']);
        Route::delete('/{id}', [EmailTemplateController::class, 'destroy']);
        Route::post('/send', [EmailTemplateController::class, 'sendMail']);
    });

    # Feature email template route
    Route::group(['prefix' => 'feature-email-templates'], function () {
        Route::get('/', [FeatureEmailTemplateController::class, 'index']);
        Route::get('/{id}', [FeatureEmailTemplateController::class, 'show']);
        Route::post('/{id}/attach-to-my-template', [FeatureEmailTemplateController::class, 'addToMyTemplates']);
    });

    # Email template tag route
    Route::group(['prefix' => 'email-template-tags'], function () {
        Route::get('/', [EmailTemplateTagController::class, 'index']);
    });
});

