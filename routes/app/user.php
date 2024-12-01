<?php

use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerWalletController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('user')->middleware(['check.suspended'])->group(function () {
    Route::middleware(['check.email.verification', 'check.default.password', 'check.suspended'])->group(function () {
        Route::post('change/password', [ChangePasswordController::class, 'changePassword'])->name('changePassword');
        Route::post('edit/profile', [ProfileController::class, 'editProfile'])->name('editProfile');
        Route::post('create-two-factor', [TwoFactorController::class, 'createTwoFactor'])->name('createTwoFactor');
        Route::post('confirm-two-factor', [TwoFactorController::class, 'confirmTwoFactor'])->name('confirmTwoFactor');
        Route::post('disable-two-factor', [TwoFactorController::class, 'disableTwoFactor'])->name('disableTwoFactor');
        Route::post('current-recovery-codes', [TwoFactorController::class, 'currentRecoveryCodes'])->name('currentRecoveryCodes');
        Route::post('new-recovery-codes', [TwoFactorController::class, 'newRecoveryCodes'])->name('newRecoveryCodes');

        Route::prefix('users')->group(function () {
            Route::post('create', [UserController::class, 'create'])->name('createUser');
            Route::put('assign', [UserController::class, 'assign'])->name('assignBranch');
            Route::delete('delete', [UserController::class, 'delete'])->name('deleteUser');
            Route::get('read/{id?}', [UserController::class, 'read'])->name('readUser');
            Route::get('model_read/{model}/{id?}', [UserController::class, 'readByUserModel'])->name('readByUserModel');
            Route::put('update', [UserController::class, 'update'])->name('updateUser');
            Route::put('suspend', [UserController::class, 'suspend'])->name('suspendUser');
        });

        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::prefix('branch')->group(function () {
            Route::post('create', [BranchController::class, 'create'])->name('createBranch');
            Route::delete('delete', [BranchController::class, 'delete'])->name('deleteBranch');
            Route::get('read/{id?}', [BranchController::class, 'read'])->name('readBranch');
            Route::put('update', [BranchController::class, 'update'])->name('updateBranch');
        });

        Route::prefix('wallet')->group(function () {
            Route::get('read/{id?}', [WalletController::class, 'read'])->name('readWallet');
        });

        Route::prefix('customer_wallet')->group(function () {
            Route::get('read/{id?}', [CustomerWalletController::class, 'read'])->name('readCustomerWallet');
        });

        Route::prefix('customer')->group(function () {
            Route::post('create', [CustomerController::class, 'create'])->name('createCustomer');
            Route::delete('delete', [CustomerController::class, 'delete'])->name('deleteCustomer');
            Route::get('read/{id?}', [CustomerController::class, 'read'])->name('readCustomer');
            Route::put('update', [CustomerController::class, 'update'])->name('updateCustomer');
        });

    });
});
