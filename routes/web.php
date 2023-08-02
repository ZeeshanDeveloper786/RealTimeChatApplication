<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\frontendController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/',[frontendController::class,'showRegister'])->name('register');
Route::get('/signin',[frontendController::class,'showSignin'])->name('signin');

Route::get('/dashboard',[frontendController::class,'showDashboard'])->name('dashboard');


