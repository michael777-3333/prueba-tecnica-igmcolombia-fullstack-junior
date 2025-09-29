<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return redirect()->route('users.index');
});

Route::resource('users', UserController::class);

// Posts (relacionados a usuarios)
Route::resource('posts', PostController::class);