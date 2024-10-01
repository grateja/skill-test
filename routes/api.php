<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\TestController;

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

Route::get('test', [TestController::class, 'test']);

Route::post('auth/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AccountsController::class, 'register']);

// /api/auth
Route::prefix('auth')->middleware('auth:sanctum')->controller(AuthController::class)->group(function() {
    // /api/auth/logout
    Route::post('logout', 'logout');

    // /api/auth/check
    Route::get('check', 'check');
});

