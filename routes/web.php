<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/image-upload', [App\Http\Controllers\ImageUploadController::class, 'storeImage'])->name('image.upload');
Route::prefix('admin')->middleware('auth','isAdmin')->group(function(){
    Route::get('dashboard',[App\Http\Controllers\Admin\DashboardController::class, 'index']);
    Route::prefix('user')->controller(App\Http\Controllers\Admin\UserController::class)->group(function () {
        Route::get('/','index')->name('userIndex');
        Route::get('/create','create');
        Route::post('/store','store');
        Route::get('/edit/{id}','edit')->middleware('checkUserAccess');
        Route::put('/update/{id}','update')->middleware('checkUserAccess');
        Route::get('/delete/{id}','destroy')->middleware('checkUserAccess');
        Route::get('/search','search');
    });
    Route::prefix('customer')->controller(App\Http\Controllers\Admin\CustomerController::class)->group(function () {
        Route::get('/','index');
        Route::get('/create','create');
        Route::post('/store','store');
        Route::get('/edit/{id}','edit');
        Route::put('/update/{id}','update');
        Route::get('/delete/{id}','destroy');
        Route::get('/search','search');
    });
    Route::prefix('category')->controller(App\Http\Controllers\Admin\CategoryController::class)->group(function () {
        Route::get('/','index')->name('categoryIndex');
        Route::get('/create','create');
        Route::post('/store','store');
        Route::get('/edit/{id}','edit');
        Route::put('/update/{id}','update');
        Route::get('/delete/{id}','destroy');
        Route::get('/search','search');
    });
    Route::prefix('producer')->controller(App\Http\Controllers\Admin\ProducerController::class)->group(function () {
        Route::get('/','index');
        Route::get('/create','create');
        Route::post('/store','store');
        Route::get('/edit/{id}','edit');
        Route::put('/update/{id}','update');
        Route::get('/delete/{id}','destroy');
        Route::get('/search','search');
    });
    Route::prefix('ingredient')->controller(App\Http\Controllers\Admin\IngredientController::class)->group(function () {
        Route::get('/','index');
        Route::get('/create','create');
        Route::post('/store','store');
        Route::get('/edit/{id}','edit');
        Route::put('/update/{id}','update');
        Route::get('/delete/{id}','destroy');
        Route::get('/search','search');
    });
    Route::prefix('hashtag')->controller(App\Http\Controllers\Admin\HashtagController::class)->group(function () {
        Route::get('/','index');
        Route::get('/create','create');
        Route::post('/store','store');
        Route::get('/edit/{id}','edit');
        Route::put('/update/{id}','update');
        Route::get('/delete/{id}','destroy');
        Route::get('/search','search');
    });
    Route::prefix('product')->controller(App\Http\Controllers\Admin\ProductController::class)->group(function () {
        Route::get('/','index');
        Route::get('/create','create');
        Route::post('/store','store');
        Route::get('/edit/{id}','edit');
        Route::put('/update/{id}','update');
        Route::get('/delete/{id}','destroy');
        Route::get('/search','search');
        Route::get('/image/delete/{imageId}','deleteImage');
        Route::get('/export-products','exportProduct')->name('exportProduct');
        Route::post('/export-products-selected','exportProductSelected')->name('exportProductSelected');
    });
    Route::prefix('voucher')->controller(App\Http\Controllers\Admin\VoucherController::class)->group(function () {
        Route::get('/','index')->name('voucherIndex');
        Route::get('/create','create');
        Route::post('/store','store');
        Route::get('/edit/{id}','edit');
        Route::put('/update/{id}','update');
        Route::get('/delete/{id}','destroy');
        Route::get('/search','search');
    });
    Route::prefix('bill')->controller(App\Http\Controllers\Admin\BillController::class)->group(function () {
        Route::get('/','index');
    });
});

