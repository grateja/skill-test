<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserManagementController;

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

// /api/user-management
Route::prefix('user-management')->middleware(['auth:sanctum'])->controller(UserManagementController::class)->group(function() {
    Route::get('/', 'index');

    // /api/user-management/update-self
    Route::post('update-self', 'updateProfile');

    // /api/user-management/change-password
    Route::post('change-password', 'changePassword');
});

Route::prefix('tasks')->controller(TaskController::class)->middleware('auth:sanctum')->group(function() {
    Route::get('/', 'index');

    Route::post('{taskId}/submit', 'submit');

    Route::middleware('role:manager,admin')->group(function() {
        // /api/tasks/create
        Route::post('create', 'store');

        // /api/tasks/{taskId}/update
        Route::post('{taskId}/update', 'store');

        // /api/tasks/{taskId}/archive
        Route::post('{taskId}/archive', 'archive');

        // /api/tasks/{taskId}/restore
        Route::post('{taskId}/restore', 'restore');
    });
});

// // /api/tasks
// Route::prefix('tasks')->middleware(['auth:sanctum', 'role:manager,admin'])->controller(TaskController::class)->group(function() {
//     // /api/tasks
//     Route::get('/', 'index');

//     // /api/tasks/create
//     Route::post('create', 'store');
// });

// // /api/tasks
// Route::prefix('my-tasks')->middleware(['auth:sanctum'])->controller(TaskController::class)->group(function() {
//     // /api/tasks
//     Route::get('/', 'index');

//     // /api/tasks/create
//     Route::post('create', 'store');
// });

