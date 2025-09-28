<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('users', UserController::class);

// Posts (relacionados a usuarios)
Route::resource('posts', PostController::class);