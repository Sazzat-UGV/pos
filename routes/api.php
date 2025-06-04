<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('user-registration',[UserController::class,'userRegistration'])->name('userRegistration');
Route::post('user-login',[UserController::class,'userLogin'])->name('userLogin');
