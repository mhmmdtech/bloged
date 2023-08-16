<?php

use App\Http\Controllers\Application\HomeController;
use App\Http\Controllers\Application\PostController;
use App\Http\Controllers\Application\CategoryController;
use App\Http\Controllers\Application\SearchController;
use Illuminate\Support\Facades\Route;

Route::permanentRedirect('/home', '/');

Route::name('application.')->group(function () {
    Route::get('/', HomeController::class)->name('home');

    Route::get('/search', SearchController::class)->name('search');

    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('index');
        Route::get('/{post}/{slug?}', [PostController::class, 'show'])->name('show');
    });

    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/{category}/{slug?}', [CategoryController::class, 'show'])->name('show');
    });
});