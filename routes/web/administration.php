<?php

use App\Http\Controllers\Administration\CategoryController;
use App\Models\Category;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('terminator')->name('administration.')->middleware(['auth', 'verified'])->group(function () {
    Route::inertia('/', 'Admin/Dashboard')->name('dashboard');

    Route::resource('categories', CategoryController::class);
});