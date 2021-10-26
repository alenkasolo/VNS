<?php

use App\Http\Controllers\MVCControllers\UserManagementController as MvcUserManagementController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::get('/login', [MvcUserManagementController::class, 'loginPage'])->name('login')->middleware('guest');
Route::post('/login', [MvcUserManagementController::class, 'login'])->middleware('guest');

Route::get('/logout', [MvcUserManagementController::class, 'logout'])->middleware('auth:web');

Route::get('forgot-password', [MvcUserManagementController::class, 'forgotPasswordPage'])->name('forgotPassword')->middleware('guest');
Route::post('forgot-password', [MvcUserManagementController::class, 'forgotPassword'])->middleware('guest');

Route::get('reset-password/{token}', [MvcUserManagementController::class, 'resetPasswordPage'])->name('password.reset')->middleware('guest');
Route::post('reset-password/{token}', [MvcUserManagementController::class, 'resetPassword'])->middleware('guest');

Route::get('/users', [MvcUserManagementController::class, 'search'])->middleware(['auth:sanctum', 'isAdmin'])->name('users');


//require __DIR__.'/auth.php';
