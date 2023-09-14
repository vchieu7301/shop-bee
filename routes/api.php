<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Admin
Route::group(['prefix' => 'admin'], function () {
    Route::post('login', [AuthController::class, 'login'])->name('admin-login');
    Route::group(["middleware" => ["isAdmin", "auth:sanctum", "cors"]], function () {
        Route::post('sign-out', [AuthController::class, 'signOut'])->name('sign-out');
        Route::resource('users', UserController::class)->only('index', 'store', 'show', 'update', 'destroy');
        Route::resource('categories', CategoryController::class)->only('index', 'store', 'show', 'update', 'destroy');
        Route::resource('products', ProductController::class)->only('index', 'store', 'show', 'update', 'destroy');
        Route::resource('orders', OrderController::class)->only('index', 'store', 'show', 'update', 'destroy');
    });
});

//Common
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::get('unauthenticated', [AuthController::class, 'unauthenticated'])->name('unauthenticated');
Route::group(["middleware" => ["auth:sanctum", "cors"]], function () {
    Route::post('sign-out', [AuthController::class, 'signOut'])->name('sign-out');
    Route::get('dashboard', 'ProductController@dashboardProducts')->name('dashboard');
    Route::post('orders', 'OrderController@palceOrder')->name('place-order');
    Route::post('cancelOrder/{id}', 'OrderController@cancelOrder')->name('cancel-order');
});
