<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExampleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MainCateController;
use App\Http\Controllers\SubCateController;
use App\Http\Controllers\ChadoContentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/example', [ExampleController::class, 'index']);

// Auth Endpoints
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Public Read-Only Endpoints
Route::get('/main-cate', [MainCateController::class, 'indexPublic']);
Route::get('/sub-cate', [SubCateController::class, 'indexPublic']);
Route::get('/chado-content', [ChadoContentController::class, 'indexPublic']);

// User Management Endpoints (Public for now based on request)
Route::apiResource('users', UserController::class);

// Admin CRUD Endpoints
Route::prefix('admin')->group(function () {
    Route::apiResource('main-cate', MainCateController::class);
    Route::apiResource('sub-cate', SubCateController::class);
    Route::apiResource('chado-content', ChadoContentController::class);
});
