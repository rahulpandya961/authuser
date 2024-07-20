<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProductApiController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [RegisterController::class, 'registerUser']);
Route::post('/loginuser', [RegisterController::class, 'loginUser']);

Route::middleware('auth:api')->group( function () {
    Route::get('products/get', [ProductApiController::class, 'index']);
    Route::post('/products/store', [ProductApiController::class, 'store']);
    Route::get('/products/{id}', [ProductApiController::class, 'show']);
    Route::put('/products/{id}', [ProductApiController::class, 'update']);
    Route::delete('/products/{id}', [ProductApiController::class, 'destroy']);
    Route::get('/admin/profile', [RegisterController::class, 'showProfile']);
    Route::post('/admin/profile/update', [RegisterController::class, 'updateProfile']);
    Route::post('/logout', [RegisterController::class, 'logoutUser']);
    
});