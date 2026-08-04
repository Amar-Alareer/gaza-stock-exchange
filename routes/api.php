<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PublicStoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (بدون تسجيل دخول) - يستخدمها موقع "وفر كاش" الرئيسي
|--------------------------------------------------------------------------
*/
Route::post('/auth/login', [AuthController::class, 'login']);

Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{id}', [ArticleController::class, 'show']);

// الكاتيجوريز (لعرض فلاتر الأصناف بالموقع)
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);

// الأصناف + مقارنة الأسعار
Route::get('/items', [ItemController::class, 'index']);
Route::get('/items/{id}', [ItemController::class, 'show']);
Route::get('/items/{id}/prices', [ItemController::class, 'prices']);

// المحلات (عرض عام فقط)
Route::get('/stores', [PublicStoreController::class, 'index']);
Route::get('/stores/{id}', [PublicStoreController::class, 'show']);


/*
|--------------------------------------------------------------------------
| Protected Routes (محمية بـ auth:sanctum) - لوحة تحكم الأدمن فقط
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // إدارة الأصناف (إضافة / تعديل / حذف)
    Route::post('items', [ItemController::class, 'store']);
    Route::put('items/{id}', [ItemController::class, 'update']);
    Route::delete('items/{id}', [ItemController::class, 'destroy']);

    // إدارة الكاتيجوريز
    Route::post('categories', [CategoryController::class, 'store']);
    Route::put('categories/{id}', [CategoryController::class, 'update']);
    Route::delete('categories/{id}', [CategoryController::class, 'destroy']);

    Route::get('/admin/dashboard-data', [DashboardController::class, 'getDashboardData']);
    Route::put('/admin/products/{id}', [DashboardController::class, 'updateProduct']);
    Route::delete('/admin/products/{id}', [DashboardController::class, 'deleteProduct']);
    Route::get('/admin/search', [DashboardController::class, 'globalSearch']);
    Route::get('/admin/notifications', [NotificationController::class, 'getNotifications']);

    // إدارة المتاجر (لوحة الأدمن)
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
