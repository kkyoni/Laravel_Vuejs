<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
*/

use App\Http\Controllers\Api\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::namespace('Api')->group(function () {
//     Route::resource('category', CategoryController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
// });
Route::namespace('Api')->group(function () {
    Route::get('category', [CategoryController::class, 'index']);
    Route::post('/category/store', [CategoryController::class, 'store']);
    Route::get('/category/edit/{id}', [CategoryController::class, 'edit']);
    Route::post('/category/update/{id}', [CategoryController::class, 'update']);
    Route::post('/category/delete/{id}', [CategoryController::class, 'destroy']);
    // Route::resource('category', CategoryController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
});
// Route::get('/category', [CategoryController::class, 'index']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
