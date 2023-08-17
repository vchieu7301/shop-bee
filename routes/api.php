<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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
        Route::resource('users', UserController::class)->only('index', 'store', 'show', 'update', 'destroy');
        Route::resource('categories', CategoryController::class)->only('index', 'store', 'show', 'update', 'destroy');
        Route::resource('products', ProductController::class)->only('index', 'store', 'show', 'update', 'destroy');
        Route::post('logout', [AuthController::class, 'logout'])->name("logout");
    });
});

//Common
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::get('unauthenticated', [AuthController::class, 'unauthenticated'])->name('unauthenticated');
Route::group(["middleware" => ["auth:sanctum", "cors"]], function () {
    Route::post('logout', [AuthController::class, 'logout'])->name("logout");
});
