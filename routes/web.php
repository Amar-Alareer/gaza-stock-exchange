<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes - مسارات الموقع
|--------------------------------------------------------------------------
*/

// --- 1. الصفحات الرئيسية والمنتجات ---
Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/load-more-products', [HomeController::class, 'loadMore'])->name('products.loadMore');
Route::get('/shop_details/{id}', [HomeController::class, 'show'])->name('products.show');
Route::get('/prices', [HomeController::class, 'prices'])->name('prices');

// --- 2. صفحة المتاجر والمقارنة والخريطة ---
Route::get('/shops', [HomeController::class, 'shops'])->name('shops');
Route::get('/shop_details', [HomeController::class, 'shop_details'])->name('shop-details');
Route::get('/map', [HomeController::class, 'map'])->name('map');
Route::get('/compare', [HomeController::class, 'compare'])->name('compare');

// --- 3. صفحات المصادقة والحساب الشخصي ---
Route::get('/login', [AuthController::class, 'loginView'])->name('login');
Route::get('/signup', [AuthController::class, 'signupView'])->name('signup');

// مسار الملف الشخصي (يمكن وضع حماية auth عليه مستقبلاً)
Route::get('/profile', [AuthController::class, 'profile'])->name('profile');