<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('customers')->group(function () {
    Route::get('/', [CustomerController::class, 'index'])->name('customer.index');
    Route::post('/', [CustomerController::class, 'store'])->name('customer.store');
    Route::get('/{id?}', [CustomerController::class, 'show'])->name('customer.show')->middleware('check-parameter');
    Route::put('/{id?}', [CustomerController::class, 'update'])->name('customer.update')->middleware('check-parameter');
    Route::delete('/{id?}', [CustomerController::class, 'destroy'])->name('customer.destroy')->middleware('check-parameter');
});

Route::prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('order.index');
    Route::post('/', [OrderController::class, 'store'])->name('order.store');
    Route::get('/{id?}', [OrderController::class, 'show'])->name('order.show')->middleware('check-parameter');
    Route::put('/{id?}', [OrderController::class, 'update'])->name('order.update')->middleware('check-parameter');
    Route::delete('/{id?}', [OrderController::class, 'destroy'])->name('order.destroy')->middleware('check-parameter');
});

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('product.index');
    Route::post('/', [ProductController::class, 'store'])->name('product.store');
    Route::get('/{id?}', [ProductController::class, 'show'])->name('product.show')->middleware('check-parameter');
    Route::put('/{id?}', [ProductController::class, 'update'])->name('product.update')->middleware('check-parameter');
    Route::delete('/{id?}', [ProductController::class, 'destroy'])->name('product.destroy')->middleware('check-parameter');
});

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('category.index');
    Route::post('/', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/{id?}', [CategoryController::class, 'show'])->name('category.show')->middleware('check-parameter');
    Route::put('/{id?}', [CategoryController::class, 'update'])->name('category.update')->middleware('check-parameter');
    Route::delete('/{id?}', [CategoryController::class, 'destroy'])->name('category.destroy')->middleware('check-parameter');
});

Route::get('calculate-discount/{id?}', [DiscountController::class, 'calculateDiscount'])->name('calculate.discount')->middleware('check-parameter');

