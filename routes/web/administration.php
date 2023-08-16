<?php

use App\Http\Controllers\Administration;
use Illuminate\Support\Facades\Route;

Route::prefix('terminator')->name('administration.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', Administration\DashboardController::class)->name('dashboard');

    Route::get('users/{user}/roles', [Administration\UserRoleController::class, 'roles'])->name('users.roles');
    Route::put('users/{user}/roles', [Administration\UserRoleController::class, 'updateRoles'])->name('users.roles.update');
    Route::get('users/{user}/permissions', [Administration\UserPermissionController::class, 'permissions'])->name('users.permissions');
    Route::put('users/{user}/permissions', [Administration\UserPermissionController::class, 'updatePermissions'])->name('users.permissions.update');
    Route::get('users/{user}/pasasword', [Administration\UserPasswordController::class, 'editPassword'])->name('users.password.edit');
    Route::put('users/{user}/pasasword', [Administration\UserPasswordController::class, 'updatePassword'])->name('users.password.update');
    Route::get('users/advanced-search', Administration\UserSearchController::class)->name('users.advanced-search');
    Route::get('users/report', [Administration\UserReportController::class, 'report'])->name('users.report');
    Route::get('users/report/{format}', [Administration\UserReportController::class, 'downloadReport'])->name('users.report.download');
    Route::resource('users', Administration\UserController::class);

    Route::get('categories/trashed', [Administration\CategoryController::class, 'trashed'])->name('categories.trashed');
    Route::delete('categories/force-delete/{category?}', [Administration\CategoryController::class, 'forceDelete'])->withTrashed()->name('categories.force-delete');
    Route::patch('categories/restore/{category}', [Administration\CategoryController::class, 'restore'])->withTrashed()->name('categories.restore');
    Route::resource('categories', Administration\CategoryController::class);

    Route::patch('/posts/{post}/toggle-featured', [Administration\PostController::class, 'toggleFeatured'])->name('posts.toggle-featured');
    Route::get('posts/trashed', [Administration\PostController::class, 'trashed'])->name('posts.trashed');
    Route::delete('posts/force-delete/{post?}', [Administration\PostController::class, 'forceDelete'])->withTrashed()->name('posts.force-delete');
    Route::patch('posts/restore/{post}', [Administration\PostController::class, 'restore'])->withTrashed()->name('posts.restore');
    Route::resource('posts', Administration\PostController::class);

    Route::resource('logs', Administration\LogController::class)->only(['index', 'show']);

    Route::get('provinces/trashed', [Administration\ProvinceController::class, 'trashed'])->name('provinces.trashed');
    Route::delete('provinces/force-delete/{province?}', [Administration\ProvinceController::class, 'forceDelete'])->withTrashed()->name('provinces.force-delete');
    Route::patch('provinces/restore/{province}', [Administration\ProvinceController::class, 'restore'])->withTrashed()->name('provinces.restore');
    Route::resource('provinces', Administration\ProvinceController::class);

    Route::resource('provinces.cities', Administration\CityController::class)->shallow();
});