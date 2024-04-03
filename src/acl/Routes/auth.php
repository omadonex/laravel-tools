<?php

declare(strict_types=1);

use Omadonex\LaravelTools\Acl\Http\Controllers\Auth\AuthenticatedSessionController;
use Omadonex\LaravelTools\Acl\Http\Controllers\Auth\ConfirmablePasswordController;
use Omadonex\LaravelTools\Acl\Http\Controllers\Auth\EmailVerificationNotificationController;
use Omadonex\LaravelTools\Acl\Http\Controllers\Auth\EmailVerificationPromptController;
use Omadonex\LaravelTools\Acl\Http\Controllers\Auth\NewPasswordController;
use Omadonex\LaravelTools\Acl\Http\Controllers\Auth\PasswordController;
use Omadonex\LaravelTools\Acl\Http\Controllers\Auth\PasswordResetLinkController;
use Omadonex\LaravelTools\Acl\Http\Controllers\Auth\RegisteredUserController;
use Omadonex\LaravelTools\Acl\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->name('auth.')->group(function (): void {

    Route::middleware('guest')->group(function (): void {
        if (config('omx.acl.auth.register')) {
            Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
            Route::post('register', [RegisteredUserController::class, 'store'])->name('postRegister');
        }
        Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('postLogin');
        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
        Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
    });

    Route::middleware('auth')->group(function (): void {
        Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])->name('verification.notice');
        Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');
        Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware('throttle:6,1')
            ->name('verification.send');
        Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
        Route::post('confirm-password', [ConfirmablePasswordController::class, 'store'])->name('password.postConfirm');
        Route::put('password', [PasswordController::class, 'update'])->name('password.update');
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    });
});
