<?php

use App\Http\Controllers\Administration\DashboardController;
use App\Http\Controllers\Administration\CategoryController;
use App\Http\Controllers\Administration\PostController;
use App\Http\Controllers\Administration\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('terminator')->name('administration.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::get('users/{user}/roles', [UserController::class, 'roles'])->name('users.roles');
    Route::put('users/{user}/roles', [UserController::class, 'updateRoles'])->name('users.roles.update');
    Route::get('users/{user}/permissions', [UserController::class, 'permissions'])->name('users.permissions');
    Route::put('users/{user}/permissions', [UserController::class, 'updatePermissions'])->name('users.permissions.update');
    Route::resource('users', UserController::class);

    Route::resource('categories', CategoryController::class);

    Route::patch('/posts/{post}/toggle-featured', [PostController::class, 'toggleFeatured'])->name('posts.toggle-featured');
    Route::resource('posts', PostController::class);
});