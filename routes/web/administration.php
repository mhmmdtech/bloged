<?php

use App\CustomTest\UniqueIdTest;
use App\Http\Controllers\Administration\DashboardController;
use App\Http\Controllers\Administration\CategoryController;
use App\Http\Controllers\Administration\CityController;
use App\Http\Controllers\Administration\PostController;
use App\Http\Controllers\Administration\UserController;
use App\Http\Controllers\Administration\LogController;
use App\Http\Controllers\Administration\ProvinceController;
use Illuminate\Support\Facades\Route;

Route::prefix('terminator')->name('administration.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::get('users/{user}/roles', [UserController::class, 'roles'])->name('users.roles');
    Route::put('users/{user}/roles', [UserController::class, 'updateRoles'])->name('users.roles.update');
    Route::get('users/{user}/permissions', [UserController::class, 'permissions'])->name('users.permissions');
    Route::put('users/{user}/permissions', [UserController::class, 'updatePermissions'])->name('users.permissions.update');
    Route::get('users/{user}/pasasword', [UserController::class, 'editPassword'])->name('users.password.edit');
    Route::put('users/{user}/pasasword', [UserController::class, 'updatePassword'])->name('users.password.update');
    Route::get('users/advanced-search', [UserController::class, 'advancedSearch'])->name('users.advanced-search');
    Route::get('users/report', [UserController::class, 'report'])->name('users.report');
    Route::get('users/report/{format}', [UserController::class, 'downloadReport'])->name('users.report.download');
    Route::resource('users', UserController::class);

    Route::resource('categories', CategoryController::class);

    Route::patch('/posts/{post}/toggle-featured', [PostController::class, 'toggleFeatured'])->name('posts.toggle-featured');
    Route::resource('posts', PostController::class);

    Route::resource('logs', LogController::class)->only(['index', 'show']);

    Route::resource('provinces', ProvinceController::class);

    Route::resource('provinces.cities', CityController::class)->shallow();
});