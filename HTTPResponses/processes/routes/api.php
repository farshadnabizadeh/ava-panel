<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\usersController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/user/createaccount',[usersController::class,'createAccount']);
Route::post('/user/session',[usersController::class,'getUserSession']);
Route::post('/user/email/session',[usersController::class,'getEmailSession']);
Route::post('/user/password/session',[usersController::class,'getPasswordSession']);
Route::post('/user/setusername',[usersController::class,'setUsername']);
