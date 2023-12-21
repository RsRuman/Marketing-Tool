<?php

use Illuminate\Support\Facades\Route;
use Moveon\Tag\Http\Controllers\TagController;


Route::group(['prefix' => 'v1', 'middleware' => ['auth:api']], function () {
    # Tags
    Route::group(['prefix' => 'tags'], function () {
        Route::get('/', [TagController::class, 'index']);
        Route::get('/{id}', [TagController::class, 'show']);
        Route::post('/', [TagController::class, 'store']);
        Route::put('/{id}', [TagController::class, 'update']);
        Route::delete('/{id}', [TagController::class, 'destroy']);
        Route::post('/attach-with-lead', [TagController::class, 'attachLeadWithTag']);
    });
});
