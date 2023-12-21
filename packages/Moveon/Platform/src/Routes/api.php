<?php

use Illuminate\Support\Facades\Route;
use Moveon\Platform\Http\Controllers\ShopifyController;


Route::group(['prefix' => 'v1/platform'], function () {
    Route::group(['prefix' => 'shopify'], function () {
        Route::get('/install', [ShopifyController::class, 'generateInstallLink'])->name('generate-install-link');
        Route::get('/auth/shopify/callback', [ShopifyController::class, 'handleCallback']);
    });
});
