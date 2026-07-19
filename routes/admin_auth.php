<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
| Customer login: /login
| Admin login:    /login/admin
*/

Route::middleware('guest')->name('admin.')->group(function () {
    Route::get('login/admin', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login/admin', [AuthenticatedSessionController::class, 'store'])->name('login.store');

    Route::prefix('admin')->group(function () {
        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
        Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
    });
});

// Old URL bookmark support
Route::redirect('admin/login', '/login/admin', 301);

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
