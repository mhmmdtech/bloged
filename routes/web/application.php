<?php

use App\Http\Controllers\Application;
use Illuminate\Support\Facades\Route;

Route::permanentRedirect('/home', '/');

Route::name('application.')->group(function () {
    Route::get('/', Application\HomeController::class)->name('home');

    Route::get('/search', Application\SearchController::class)->name('search');

    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('/', [Application\PostController::class, 'index'])->name('index');
        Route::get('/{post}/{slug?}', [Application\PostController::class, 'show'])->name('show');
    });

    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [Application\CategoryController::class, 'index'])->name('index');
        Route::get('/{category}/{slug?}', [Application\CategoryController::class, 'show'])->name('show');
    });
});