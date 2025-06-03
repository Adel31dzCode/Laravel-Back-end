<?php

use App\Http\Controllers\tokenController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\GoogleAuthController;
use Laravel\Socialite\Facades\Socialite;



Route::get('/currentUser', function (Request $request) {
     return $request->user();
})->middleware('auth:sanctum');


Route::post("user/register", [UserController::class, "register"]);
Route::post("user/login", [UserController::class, "login"]);



Route::post('/refresh-token', [tokenController::class, 'refreshToken']);


Route::post('/email/send-code', [EmailVerificationController::class, 'sendVerificationCode']);
Route::post('/email/verify-code', [EmailVerificationController::class, 'verifyCode']);


Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);
