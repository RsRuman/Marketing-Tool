<?php
//
//use Illuminate\Support\Facades\Route;
//use Moveon\Customer\Http\Controllers\CustomerController;
//use Moveon\Customer\Http\Controllers\ExportController;
//use Moveon\Customer\Http\Controllers\FilterController;
//use Moveon\Customer\Http\Controllers\ImportController;
//use Moveon\Customer\Http\Controllers\SegmentationController;
//
//
//Route::group(['prefix' => 'v1', 'middleware' => ['auth:api']], function () {
//    # Import data
//    Route::group(['prefix' => 'import'], function () {
//        # Shopify
//        Route::get('/customers', [ImportController::class, 'importCustomers']);
//        Route::get('/orders', [ImportController::class, 'importOrders']);
//        Route::get('/products', [ImportController::class, 'importProducts']);
//    });
//
//    Route::group(['prefix' => 'export'], function () {
//        # Export
//        Route::get('/customers', [ExportController::class, 'exportCustomer']);
//    });
//
//    Route::group(['prefix' => 'import'], function () {
//        # Import
//        Route::post('/customers', [ExportController::class, 'importCustomer']);
//    });
//
//
//    # Customer
//    Route::group(['prefix' => 'customers'], function () {
//        Route::get('/', [CustomerController::class, 'index']);
//        Route::get('/{id}', [CustomerController::class, 'show']);
//    });
//
//    # Filter
//    Route::group(['prefix' => 'filters'], function () {
//        Route::get('/', [FilterController::class, 'index']);
//    });
//
//    # Segmentation
//    Route::group(['prefix' => 'segmentations'], function () {
//        Route::get('/', [SegmentationController::class, 'index']);
//        Route::get('/{slug}', [SegmentationController::class, 'show']);
//        Route::post('/', [SegmentationController::class, 'store']);
//        Route::put('/{slug}', [SegmentationController::class, 'update']);
//        Route::delete('/{slug}', [SegmentationController::class, 'destroy']);
//    });
//});
//
//
