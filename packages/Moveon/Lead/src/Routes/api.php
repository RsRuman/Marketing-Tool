<?php

use Illuminate\Support\Facades\Route;
use Moveon\Lead\Http\Controllers\LeadController;


Route::group(['prefix' => 'v1', 'middleware' => ['auth:api']], function () {
    # Lead
    Route::group(['prefix' => 'leads'], function () {
        Route::get('/', [LeadController::class, 'index']);
        Route::get('/{id}', [LeadController::class, 'show']);
    });
});
