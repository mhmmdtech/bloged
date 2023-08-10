<?php

use App\Http\Controllers\Administration\CategoryController;
use App\Http\Controllers\Administration\PostController;
use App\Http\Controllers\Administration\UserController;
use App\Models\Category;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('terminator')->name('administration.')->middleware(['auth', 'verified'])->group(function () {
    Route::inertia('/', 'Admin/Dashboard')->name('dashboard');

    Route::resource('users', UserController::class);

    Route::resource('categories', CategoryController::class);

    Route::patch('/posts/{post}/toggle-featured', [PostController::class, 'toggleFeatured'])->name('posts.toggle-featured');
    Route::resource('posts', PostController::class);
});