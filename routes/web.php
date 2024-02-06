<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//check user type
Route::middleware('user_type:admin,superadmin')->get('/user-list', [UserController::class, 'index'])->name('user-list');

//get user list
Route::get('/users', [UserController::class, 'list'])->name('list');

//get user details
Route::get('/users/{user}', [UserController::class, 'userDetails'])->name('user-details');

//update user
Route::post('/user-update/{user}', [UserController::class, 'update'])->name('user-update');

//delete user
Route::post('/user-delete/{user}', [UserController::class, 'delete'])->name('user-delete');
