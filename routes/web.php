<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserProductController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/register', [UserController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [UserController::class, 'register']);
Route::get('/user/login', [UserController::class, 'showLoginForm'])->name('user.login');
Route::post('/user/login', [UserController::class, 'userLogin'])->name('store.login.user');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');
Route::prefix('admin')->group(function () {
Route::get('login', [AdminController::class, 'showAdminLoginForm'])->name('login');
Route::post('login', [AdminController::class, 'adminLogin']);
Route::post('logout', [AdminController::class, 'adminLogout'])->name('logout.admin');
Route::get('/profile', [AdminController::class, 'showProfile'])->name('admin.profile');
Route::post('/profile', [AdminController::class, 'updateProfile']);
});
Route::middleware(['auth'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'userDashboard'])->name('auth.user.dashboard');
    Route::get('/user/products', [UserProductController::class,'index'])->name('auth.user.products.index');
    Route::get('/user/products/create', [UserProductController::class,'create'])->name('auth.user.products.create');
    Route::post('/user/products/store', [UserProductController::class,'store'])->name('auth.user.products.store');
    Route::get('/user/products/edit/{id}', [UserProductController::class,'edit'])->name('auth.user.products.edit');
    Route::put('/user/products/update', [UserProductController::class,'update'])->name('auth.user.products.update');
    Route::get('/user/products/show/{id}', [UserProductController::class,'show'])->name('auth.user.products.show');
    Route::delete('/user/products/destroy/{id}', [UserProductController::class,'destroy'])->name('auth.user.products.destroy');
    Route::get('/user/profile', [UserController::class, 'showProfile'])->name('user.profile');
    Route::post('/user/profile', [UserController::class, 'updateProfile']);
});
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('products', [ProductController::class,'index'])->name('auth.admin.products.index');
    Route::get('products/create', [ProductController::class,'create'])->name('auth.admin.products.create');
    Route::post('products/store', [ProductController::class,'store'])->name('auth.admin.products.store');
    Route::get('products/edit/{id}', [ProductController::class,'edit'])->name('auth.admin.products.edit');
    Route::put('products/update', [ProductController::class,'update'])->name('auth.admin.products.update');
    Route::get('products/show/{id}', [ProductController::class,'show'])->name('auth.admin.products.show');
    Route::delete('products/destroy/{id}', [ProductController::class,'destroy'])->name('auth.admin.products.destroy');
    Route::get('dashboard', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');
});


