<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes - مسارات الموقع
|--------------------------------------------------------------------------
*/

// --- 1. الصفحات العامة والمنتجات (متاحة للزوار) ---
Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/load-more-products', [HomeController::class, 'loadMore'])->name('products.loadMore');
Route::get('/product_details/{id}', [HomeController::class, 'show'])->name('products.show');
Route::get('/shop_details/{id?}', [HomeController::class, 'shop_details'])->name('shop-details.show');
Route::get('/shop_details', [HomeController::class, 'shop_details'])->name('shop-details');
Route::get('/prices', [HomeController::class, 'prices'])->name('prices');
Route::get('/shops', [HomeController::class, 'shops'])->name('shops');
Route::get('/map', [HomeController::class, 'map'])->name('map');
Route::get('/compare', [HomeController::class, 'compare'])->name('compare');
Route::get('/search/live', [HomeController::class, 'liveSearch'])->name('search.live');

// --- 2. صفحات تسجيل الدخول وإنشاء الحساب وتسجيل الخروج والربط مع جوجل ---
Route::get('/login', [AuthController::class, 'loginView'])->name('login');
Route::post('/login', [AuthController::class, 'webLogin'])->name('login.post');
Route::get('/signup', [AuthController::class, 'signupView'])->name('signup');
Route::post('/signup', [AuthController::class, 'webRegister'])->name('signup.post');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'webLogout'])->name('logout');

// Google OAuth
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// --- 3. المسارات المقيدة (تتطلب تسجيل دخول حصراً للمستخدمين المسجلين) ---
Route::middleware('auth')->group(function () {
    // الملف الشخصي وإدارته
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [AuthController::class, 'updateWebProfile'])->name('profile.update');

    // إدارة المفضلة (سلع ومحلات)
    Route::post('/favorites/toggle', [AuthController::class, 'toggleFavorite'])->name('favorites.toggle');
    Route::get('/favorites/check', [AuthController::class, 'checkFavorite'])->name('favorites.check');
});