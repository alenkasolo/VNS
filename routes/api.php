<?php

use App\Http\Controllers\RestControllers\UserManagementController as RestUserManagementController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware(['auth:sanctum', 'isAdmin'])->prefix('/users')->group(function () {
    Route::post('', [RestUserManagementController::class, 'create'])->name('users');
    Route::post('create-many', [RestUserManagementController::class, 'createMany'])->name('create-many-users');
    Route::prefix('/{id}')->where(['id' => '^[A-Za-z]{1}([A-Za-z]|[0-9]){5,7}$'])->group(function () {
        Route::get('', [RestUserManagementController::class, 'get'])->name('user');
        Route::put('', [RestUserManagementController::class, 'update']);
        Route::delete('', [RestUserManagementController::class, 'delete']);
    });
});
