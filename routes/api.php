<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgatePasswordController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function (){
    Route::post('/login',[AuthController::class,'login']);
    Route::post('/register',[AuthController::class,'register']);
    Route::post('/forgate',[ForgatePasswordController::class,'ForgatePassword']);

    Route::get('/login',function (){
        return response()->json('Unauthenticated');
    })->name('login');

    Route::middleware('auth:api')->group(function (){
        Route::post('/logout',[AuthController::class,'logout']);
    });
});
