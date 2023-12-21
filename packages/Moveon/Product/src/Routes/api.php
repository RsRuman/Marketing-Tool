<?php

use Illuminate\Support\Facades\Route;
use Moveon\Product\Http\Controllers\ProductController;


Route::group(['prefix' => 'v1', 'middleware' => ['auth:api']], function () {
    # Product Route
    Route::group(['prefix' => 'products'], function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{id}', [ProductController::class, 'show']);
    });
});
