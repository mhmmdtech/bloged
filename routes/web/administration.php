<?php

use App\Http\Controllers\Administration\DashboardController;
use App\Http\Controllers\Administration\CategoryController;
use App\Http\Controllers\Administration\CityController;
use App\Http\Controllers\Administration\PostController;
use App\Http\Controllers\Administration\UserController;
use App\Http\Controllers\Administration\LogController;
use App\Http\Controllers\Administration\ProvinceController;
use App\Http\Controllers\Administration\UserPasswordController;
use App\Http\Controllers\Administration\UserPermissionController;
use App\Http\Controllers\Administration\UserReportController;
use App\Http\Controllers\Administration\UserRoleController;
use App\Http\Controllers\Administration\UserSearchController;
use Illuminate\Support\Facades\Route;

Route::prefix('terminator')->name('administration.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::get('users/{user}/roles', [UserRoleController::class, 'roles'])->name('users.roles');
    Route::put('users/{user}/roles', [UserRoleController::class, 'updateRoles'])->name('users.roles.update');
    Route::get('users/{user}/permissions', [UserPermissionController::class, 'permissions'])->name('users.permissions');
    Route::put('users/{user}/permissions', [UserPermissionController::class, 'updatePermissions'])->name('users.permissions.update');
    Route::get('users/{user}/pasasword', [UserPasswordController::class, 'editPassword'])->name('users.password.edit');
    Route::put('users/{user}/pasasword', [UserPasswordController::class, 'updatePassword'])->name('users.password.update');
    Route::get('users/advanced-search', UserSearchController::class)->name('users.advanced-search');
    Route::get('users/report', [UserReportController::class, 'report'])->name('users.report');
    Route::get('users/report/{format}', [UserReportController::class, 'downloadReport'])->name('users.report.download');
    Route::resource('users', UserController::class);

    Route::get('categories/trashed', [CategoryController::class, 'trashed'])->name('categories.trashed');
    Route::delete('categories/force-delete/{category?}', [CategoryController::class, 'forceDelete'])->withTrashed()->name('categories.force-delete');
    Route::patch('categories/restore/{category}', [CategoryController::class, 'restore'])->withTrashed()->name('categories.restore');
    Route::resource('categories', CategoryController::class);

    Route::patch('/posts/{post}/toggle-featured', [PostController::class, 'toggleFeatured'])->name('posts.toggle-featured');
    Route::get('posts/trashed', [PostController::class, 'trashed'])->name('posts.trashed');
    Route::delete('posts/force-delete/{post?}', [PostController::class, 'forceDelete'])->withTrashed()->name('posts.force-delete');
    Route::patch('posts/restore/{post}', [PostController::class, 'restore'])->withTrashed()->name('posts.restore');
    Route::resource('posts', PostController::class);

    Route::resource('logs', LogController::class)->only(['index', 'show']);

    Route::get('provinces/trashed', [ProvinceController::class, 'trashed'])->name('provinces.trashed');
    Route::delete('provinces/force-delete/{province?}', [ProvinceController::class, 'forceDelete'])->withTrashed()->name('provinces.force-delete');
    Route::patch('provinces/restore/{province}', [ProvinceController::class, 'restore'])->withTrashed()->name('provinces.restore');
    Route::resource('provinces', ProvinceController::class);

    Route::resource('provinces.cities', CityController::class)->shallow();
});