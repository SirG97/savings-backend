<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [RegisterController::class, 'register'])->name('register');
    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::post('admin/login', [LoginController::class, 'superAdminLogin'])->name('superAdmin.login');
    Route::post('manager/login', [LoginController::class, 'adminLogin'])->name('login');
    Route::middleware('auth:sanctum')->post('logout', [LogoutController::class, 'logout'])->name('logout');
    Route::middleware('auth:sanctum')->post('logout-from-all-sessions', [LogoutController::class, 'logoutFromAllSessions'])->name('logoutFromAllSessions');
    Route::get('verify/email/{id}', [VerificationController::class, 'verifyUserEmail'])->name('verification.verify');
    Route::middleware('auth:sanctum')->post('resend/verify/email', [VerificationController::class, 'resendUserEmailVerification'])->name('verification.resend');
    Route::post('forgot/password', [ForgotPasswordController::class, 'forgotPassword'])->name('forgotPassword');
    Route::post('reset/password', [ResetPasswordController::class, 'resetPassword'])->name('resetPassword');
});
