<?php

use Illuminate\Support\Facades\Route;
use Moveon\Segmentation\Http\Controllers\FilterController;
use Moveon\Segmentation\Http\Controllers\SegmentationController;


Route::group(['prefix' => 'v1', 'middleware' => ['auth:api']], function () {
    # Filter
    Route::group(['prefix' => 'filters'], function () {
        Route::get('/', [FilterController::class, 'index']);
    });

    Route::group(['prefix' => 'segmentations'], function () {
        Route::get('/', [SegmentationController::class, 'index']);
        Route::get('/{id}', [SegmentationController::class, 'show']);
        Route::post('/', [SegmentationController::class, 'store']);
        Route::put('/{id}', [SegmentationController::class, 'update']);
        Route::delete('/{id}', [SegmentationController::class, 'delete']);
    });
});
