<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/v2/member/register',[LoginController::class,'register']);
Route::post('/v2/member/login',[LoginController::class,'login']);
Route::get('/v2/system/provinces',[App\Http\Controllers\Api\ProductController::class, 'provinces']);
Route::get('/v2/system/provinces/agency_list',[App\Http\Controllers\Api\ProductController::class, 'agency_list']);
Route::group(['middleware'=> ['auth:customer-api']], function(){
    //Route::get('profile', [Logi::class,'profile']);
    Route::get('/v2/member/logout',[LoginController::class,'logout']);
    Route::post('/v2/product/index', [App\Http\Controllers\Api\ProductController::class, 'index']);
    Route::get('/v2/system/homepage', [App\Http\Controllers\Api\ProductController::class, 'home']);
    Route::post('/v2/cart/update',[App\Http\Controllers\Api\ProductController::class, 'addCart']);
    Route::post('/v2/cart/index',[App\Http\Controllers\Api\ProductController::class, 'indexCart']);
    Route::get('/v2/cart/list_voucher',[App\Http\Controllers\Api\VoucherController::class, 'list_voucher']);
    Route::post('/v2/cart/discount',[App\Http\Controllers\Api\VoucherController::class, 'discount']);
    Route::post('/v2/cart/payment',[App\Http\Controllers\Api\ProductController::class, 'payment']);
    Route::get('/v2/system/category',[App\Http\Controllers\Api\CategoryController::class, 'category']);
    Route::post('/v2/system/category_type',[App\Http\Controllers\Api\CategoryController::class, 'category_type']);
    Route::post('/v2/search',[App\Http\Controllers\Api\ProductController::class, 'search']);
});

