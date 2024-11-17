<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
/**
 * @group No Auth APIs
 *
 * APIs that do not require User authentication
 */

/**
 * @group Auth APIs
 *
 * APIs that require User authentication
 *
 * @subgroup Require Pin APIs
 */

Route::prefix('auth')->group(function () {
    Route::post('register', [\App\Http\Controllers\Auth\RegisterController::class,
        'register'])->name('register');
    Route::post('login', [\App\Http\Controllers\Auth\LoginController::class,
        'login'])->name('login');
    Route::middleware('auth:sanctum')->post('logout', [\App\Http\Controllers\Auth\LogoutController::class,
        'logout'])->name('logout');
    Route::middleware('auth:sanctum')->post('logout-from-all-sessions', [\App\Http\Controllers\Auth\LogoutController::class,
        'logoutFromAllSessions'])->name('logoutFromAllSessions');
    Route::get('verify/email/{id}', [\App\Http\Controllers\Auth\VerificationController::class,
        'verifyUserEmail'])->name('verification.verify');
    Route::middleware('auth:sanctum')->post('resend/verify/email', [\App\Http\Controllers\Auth\VerificationController::class,
        'resendUserEmailVerification'])->name('verification.resend');
    Route::post('forgot/password', [\App\Http\Controllers\Auth\ForgotPasswordController::class,
        'forgotPassword'])->name('forgotPassword');
    Route::post('reset/password', [\App\Http\Controllers\Auth\ResetPasswordController::class,
        'resetPassword'])->name('resetPassword');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('change/password', [\App\Http\Controllers\Auth\ChangePasswordController::class,
        'changePassword'])->name('changePassword');
    Route::post('edit/profile', [\App\Http\Controllers\Auth\ProfileController::class,
        'editProfile'])->name('editProfile');
    Route::post('create-two-factor',
        [App\Http\Controllers\Auth\TwoFactorController::class, 'createTwoFactor'])->name('createTwoFactor');
    Route::post('confirm-two-factor',
        [App\Http\Controllers\Auth\TwoFactorController::class, 'confirmTwoFactor'])->name('confirmTwoFactor');
    Route::post('disable-two-factor',
        [App\Http\Controllers\Auth\TwoFactorController::class, 'disableTwoFactor'])->name('disableTwoFactor');
    Route::post('current-recovery-codes',
        [App\Http\Controllers\Auth\TwoFactorController::class, 'currentRecoveryCodes'])->name('currentRecoveryCodes');
    Route::post('new-recovery-codes',
        [App\Http\Controllers\Auth\TwoFactorController::class, 'newRecoveryCodes'])->name('newRecoveryCodes');
});
