<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return redirect()->route('users.index');
});

Route::resource('users', UserController::class);

// API Routes para usuarios
Route::prefix('api')->group(function () {
    Route::get('/users', [UserController::class, 'apiIndex']);
    Route::post('/users', [UserController::class, 'apiStore']);
    Route::put('/users/{user}', [UserController::class, 'apiUpdate']);
    Route::delete('/users/{user}', [UserController::class, 'apiDestroy']);
});

// Posts (relacionados a usuarios)
Route::resource('posts', PostController::class);