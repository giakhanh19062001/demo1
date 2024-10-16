<?php

use App\Http\Controllers\googleController;
use App\Http\Controllers\stripeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('auth/google',[googleController::class,'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback',[googleController::class,'handleGoogleCallBack'])->name('auth.google.callback');

Route::post('stripe',[stripeController::class,'stripe'])->name('stripe');
Route::get('success',[stripeController::class,'success'])->name('success');
Route::get('cancel',[stripeController::class,'cancel'])->name('cancel');

