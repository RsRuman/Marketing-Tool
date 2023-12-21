<?php

use Illuminate\Support\Facades\Route;
use Moveon\User\Http\Controllers\AuthController;
use Moveon\User\Http\Controllers\PermissionController;
use Moveon\User\Http\Controllers\RoleController;
use Moveon\User\Http\Controllers\UserController;


Route::group(['prefix' => 'v1/', 'middleware' => 'api'], function () {
    # Public route
    Route::post('sign-up', [AuthController::class, 'signUp']);
    Route::post('sign-in', [AuthController::class, 'signIn']);

    # Authenticate route
    Route::group(['middleware' => ['auth:api']], function () {
        Route::post('sign-out', [AuthController::class, 'signOut']);

        # User
        Route::group(['prefix' => 'users'], function () {
            Route::get('/', [UserController::class, 'index']);
            Route::post('/', [UserController::class, 'create']);
            Route::put('/{id}', [UserController::class, 'update']);
            Route::get('/{id}', [UserController::class, 'show']);
            Route::post('/{id}/status', [UserController::class, 'activeInactiveUser']);
            Route::post('/{id}/roles', [UserController::class, 'assignRoles']);
        });

        # Role
        Route::group(['prefix' => 'roles'], function () {
            Route::get('/', [RoleController::class, 'index']);
            Route::post('/', [RoleController::class, 'create']);
            Route::get('/{id}', [RoleController::class, 'show']);
            Route::put('/{id}', [RoleController::class, 'update']);
            Route::delete('/{id}', [RoleController::class, 'destroy']);
            Route::post('/{id}/permissions', [RoleController::class, 'assignPermissionToRole']);
        });

        # Permission
        Route::group(['prefix' => 'permissions'], function () {
            Route::get('/', [PermissionController::class, 'index']);
        });
    });
});
