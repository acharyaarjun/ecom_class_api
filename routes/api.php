<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::get('/category', [CategoryController::class, 'getCategories']);
Route::get('/category/{id}', [CategoryController::class, 'getCategory']);
Route::get('/category/delete/{id}', [CategoryController::class, 'deleteCategory']);

Route::post('/category/add', [CategoryController::class, 'postAddCategory']);

Route::resource('product', ProductController::class);