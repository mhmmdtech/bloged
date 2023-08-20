<?php

use App\Http\Controllers\Auth;
use App\Http\Controllers\CaptchaController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('captcha', CaptchaController::class)->name('captcha');

    Route::get('register', [Auth\RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [Auth\RegisteredUserController::class, 'store']);

    Route::get('login', [Auth\AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [Auth\AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [Auth\PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [Auth\PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [Auth\NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [Auth\NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {

    Route::post('verify-code', [Auth\EmailVerificationCodeController::class, 'verify'])
        ->name('verification-code.verify');

    Route::get('verify-email', Auth\EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', Auth\VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [Auth\EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [Auth\ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [Auth\ConfirmablePasswordController::class, 'store']);

    Route::put('password', [Auth\PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [Auth\AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});