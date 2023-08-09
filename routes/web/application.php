<?php

use App\Http\Controllers\Application\HomeController;
use App\Http\Controllers\Application\PostController;
use App\Http\Controllers\Application\CategoryController;
use Illuminate\Support\Facades\Route;

Route::permanentRedirect('/home', '/');

Route::name('application.')->group(function () {
    Route::get('/', HomeController::class)->name('home');
    Route::resource('posts', PostController::class)->only(['index', 'show']);
    Route::resource('categories', CategoryController::class)->only(['index', 'show']);
});