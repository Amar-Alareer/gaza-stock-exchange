<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{id}', [ArticleController::class, 'show']);





Route::middleware('auth:sanctum')->group(function () {

Route::get('categories', [CategoryController::class, 'index']);
Route::post('categories', [CategoryController::class, 'store']);
Route::get('categories/{id}', [CategoryController::class, 'show']);
Route::put('categories/{id}', [CategoryController::class, 'update']);
Route::delete('categories/{id}', [CategoryController::class, 'destroy']);

    Route::get('admin/items', [ItemController::class, 'index']);
    Route::post('admin/items', [ItemController::class, 'store']);
    Route::put('admin/items/{id}', [ItemController::class, 'update']);
    Route::delete('admin/items/{id}', [ItemController::class, 'destroy']);


    Route::get('/admin/dashboard-data', [DashboardController::class, 'getDashboardData']);
    Route::put('/admin/products/{id}', [DashboardController::class, 'updateProduct']);
    Route::delete('/admin/products/{id}', [DashboardController::class, 'deleteProduct']);
    Route::get('/admin/search', [DashboardController::class, 'globalSearch']);
    Route::get('/admin/notifications', [NotificationController::class, 'getNotifications']);

    // إدارة المتاجر
    Route::get('/admin/stores', [StoreController::class, 'index']);
    Route::get('/admin/stores/{id}', [StoreController::class, 'show']);
    Route::post('/admin/stores', [StoreController::class, 'store']);
    Route::post('/admin/stores/{id}', [StoreController::class, 'update']);
    Route::delete('/admin/stores/{id}', [StoreController::class, 'destroy']);

    Route::post('/articles', [ArticleController::class, 'store']);
    Route::put('/articles/{id}', [ArticleController::class, 'update']);
    Route::delete('/articles/{id}', [ArticleController::class, 'destroy']);
    // تسجيل الخروج
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::get('/admin/profile', [AuthController::class, 'getProfile']);
    Route::post('/admin/profile', [AuthController::class, 'updateProfile']);

    // جلب بيانات المستخدم الحالي
    Route::get('/auth/user', function (Request $request) {
        $user = $request->user();
        $user->append('profile_picture_url');

        return $user;
    });


});
