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
    Route::group(["middleware" => ["isAdmin", "auth:api", "cors"]], function () {
        Route::post('sign-out', [AuthController::class, 'signOut'])->name('sign-out');
        Route::post('change-password', [UserController::class, 'changePassword'])->name('change-password');
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
Route::get('dashboard', [ProductController::class, 'dashboardProducts'])->name('dashboard');
Route::get('display-product/{id}', [ProductController::class, 'displayProduct'])->name('display-product');
Route::group(["middleware" => ["auth:api", "cors"]], function () {
    Route::post('sign-out', [AuthController::class, 'signOut'])->name('sign-out');
    Route::post('orders/palce-order', [OrderController::class, 'palceOrder'])->name('place-order');
    Route::post('cancelOrder/{id}', [OrderController::class, 'cancelOrder'])->name('cancel-order');
});
