<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// /user ko route lekni thau
Route::group(['prefix' => 'user'], function() {


    // /user login vaiesakey paxiko route lekni thau
    Route::group(['middleware' => 'auth'], function(){
        Route::get('/me', [HomeController::class, 'user']);
        Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('user.home');
    });
});


// Route::get('/user', [HomeController::class, 'user'])->middleware('auth');

// /admin ko route lekhni thau
Route::group(['prefix' => 'admin'], function(){
    // login xaina vaney
    Route::group(['middleware' => 'admin.guest'], function(){
        Route::view('/login', 'admin.login')->name('admin.login');
        Route::post('/login', [AdminController::class, 'login'])->name('postAdminLogin');
    });

    // login vaesaeypaxi
    Route::group(['middleware' => 'admin'], function(){
        Route::view('/dashboard', 'admin.dashboard')->name('admin.home');


    });
});
