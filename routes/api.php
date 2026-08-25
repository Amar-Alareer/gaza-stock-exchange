<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AutomationController;


Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/articles', [ArticleController::class, 'index']);

Route::get('/stores/get-face-prices/{id}', [AutomationController::class, 'getFacePrices']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/admin/dashboard-data', [DashboardController::class, 'getDashboardData']);
    Route::put('/admin/products/{id}', [DashboardController::class, 'updateProduct']);
    Route::delete('/admin/products/{id}', [DashboardController::class, 'deleteProduct']);
    Route::get('/admin/search', [DashboardController::class, 'globalSearch']);

    Route::post('/articles', [ArticleController::class, 'store']);
    Route::put('/articles/{id}', [ArticleController::class, 'update']);
    Route::delete('/articles/{id}', [ArticleController::class, 'destroy']);
    // تسجيل الخروج
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // جلب بيانات المستخدم الحالي
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/user', function (Request $request) {
        return $request->user();
    });
});
