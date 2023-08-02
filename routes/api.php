<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authController;
use App\Http\Controllers\chatController;
use Illuminate\Support\Facades\Auth;

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
Route::post('register', [authController::class,'register']);
Route::post('login', [authController::class,'login']);

Route::middleware('auth:api')->group(function (){
    Route::post('/logout', [authController::class,'logout']);
    Route::post('fetchmessages',[chatController::class,'fetchMessage']);
    Route::post('messages',[chatController::class,'sendMessage']);

});



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

