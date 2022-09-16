<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/category', [CategoryController::class, 'getCategories']);
    Route::get('/category/{id}', [CategoryController::class, 'getCategory']);
    Route::get('/category/delete/{id}', [CategoryController::class, 'deleteCategory']);
    Route::post('/category/add', [CategoryController::class, 'postAddCategory']);
    Route::post('/category/edit/{id}', [CategoryController::class, 'postEditCategory']);

    Route::get('/category/{id}/products', [CategoryController::class, 'getProductsWithCategory']);

    Route::post('/product/{id}', [ProductController::class, 'update']);
    Route::resource('product', ProductController::class);
});
