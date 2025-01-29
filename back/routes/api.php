<?php

use App\Http\Controllers\API\AddressController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::get('getProfile', 'getProfile')->middleware('auth:sanctum');
    Route::post('updateProfile', 'updateProfile')->middleware('auth:sanctum');
    Route::post('changePassword', 'changePassword')->middleware('auth:sanctum');
});


Route::controller(ProductController::class)->prefix('products')->group(function(){
    Route::get('', 'index');
    Route::get('/{id}', 'show');
});

Route::controller(CategoryController::class)->prefix('categories')->group(function(){
    Route::get('', 'index');
    Route::get('/{id}', 'show');
});

Route::controller(AddressController::class)->prefix('addresses')->middleware('auth:sanctum')->group(function(){
    Route::get('', 'index');
    Route::post('/store', 'store');
    Route::get('/{address}', 'edit');
    Route::post('/update/{address}', 'update');
    Route::get('/destroy/{address}', 'destroy');
});
