<?php

use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerTransactionController;
use App\Http\Controllers\CustomerWalletController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoanApplicationController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('user')->middleware(['check.suspended'])->group(function () {
    Route::post('change/password', [ChangePasswordController::class, 'changePassword'])->name('changePassword');
    Route::middleware(['check.email.verification', 'check.default.password', 'check.suspended'])->group(function () {

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
            Route::get('branch_read/{branch_id}/{id?}', [UserController::class, 'readByBranchId'])->name('readByBranchId');
            Route::put('update', [UserController::class, 'update'])->name('updateUser');
            Route::put('suspend', [UserController::class, 'suspend'])->name('suspendUser');
        });

        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::prefix('dashboard')->group(function () {
            Route::get('read/{id?}', [DashboardController::class, 'read'])->name('readDashboard');
            Route::get('user_read/{id?}', [DashboardController::class, 'readByUserId'])->name('readDashboardByUserId');
            Route::get('customer_read/{id}', [DashboardController::class, 'readByCustomerId'])->name('readDashboardByCustomerId');
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
            Route::get('branch_read/{branch_id}/{id?}',[CustomerController::class, 'readByBranchId'])->name('readCustomerByBranchId');
            Route::get('user_read/{id?}',[CustomerController::class, 'readByUserId'])->name('readCustomerByUserId');
            Route::put('update', [CustomerController::class, 'update'])->name('updateCustomer');
        });

        Route::prefix('transaction')->group(function () {
            Route::post('create', [TransactionController::class, 'create'])->name('createTransaction');
//            Route::delete('delete', [TransactionController::class, 'delete'])->name('deleteTransactions');
            Route::get('read/{id?}', [TransactionController::class, 'read'])->name('readTransaction');
            Route::get('type_read/{transaction_type}/{id?}',[TransactionController::class, 'readByTransactionType'])->name('readTransactionByTransactionType');
            Route::get('branch_read/{transaction_type}/{branch_id}/{id?}',[TransactionController::class, 'readByTransactionTypeAndBranchId'])->name('readTransactionByTransactionTypeAndBranchId');
            Route::get('user_read/{transaction_type}/{user_id}/{id?}',[TransactionController::class, 'readByTransactionTypeAndUserId'])->name('readTransactionByTransactionTypeAndUserId');
//            Route::put('update', [TransactionController::class, 'update'])->name('updateTransactions');

        });

        Route::prefix('customer_transaction')->group(function () {

            Route::post('create', [CustomerTransactionController::class, 'create'])->name('createCustomerTransaction');
//            Route::delete('delete', [CustomerTransactionController::class, 'delete'])->name('deleteCustomerTransaction');
            Route::get('read/{id?}', [CustomerTransactionController::class, 'read'])->name('readCustomerTransaction');
            Route::get('user_read/{transaction_type}/{user_id}/{id?}', [CustomerTransactionController::class, 'readByTransactionTypeAndUser'])->name('readByTransactionTypeAndUser');
            Route::get('customer_read/{transaction_type}/{customer_id}/{id?}', [CustomerTransactionController::class, 'readByTransactionTypeAndCustomer'])->name('readByTransactionTypeAndCustomer');
            Route::get('type_read/{transaction_type}/{id?}',[CustomerTransactionController::class, 'readByTransactionType'])->name('readCustomerTransactionByTransactionType');
//            Route::put('update', [CustomerTransactionController::class, 'update'])->name('updateCustomerTransaction');

        });

        Route::prefix('search')->group(function () {
            Route::get('user/{value}', [SearchController::class, 'user'])->name('userSearch');
            Route::get('customer/{value}', [SearchController::class, 'customer'])->name('customerSearch');
            Route::get('customer_transaction/{value}', [SearchController::class, 'customerTransaction'])->name('customerTransactionSearch');
            Route::get('transaction/{value}', [SearchController::class, 'transaction'])->name('transactionSearch');
        });

        Route::prefix('loan')->group(function () {
            Route::post('create', [LoanController::class, 'create'])->name('createLoan');
            Route::get('read/{id?}', [LoanController::class, 'read'])->name('readLoan');
            Route::put('update', [LoanController::class, 'update'])->name('updateLoan');
            Route::delete('delete', [LoanController::class, 'delete'])->name('deleteLoan');

        });

        Route::prefix('loan_application')->group(function () {
            Route::post('create', [LoanApplicationController::class, 'create'])->name('createLoanApplication');
            Route::get('read/{id?}', [LoanApplicationController::class, 'read'])->name('readLoanApplication');
            Route::put('update', [LoanApplicationController::class, 'update'])->name('updateLoanApplication');
            Route::put('approve', [LoanApplicationController::class, 'update'])->name('approveLoanApplication');
            Route::put('reject', [LoanApplicationController::class, 'update'])->name('rejectLoanApplication');
            Route::get('customer_read/{customer_id}', [LoanApplicationController::class, 'readByCustomerId'])->name('readByCustomer');
            Route::get('user_read/{user_id}/{status?}', [LoanApplicationController::class, 'readByUserId'])->name('readByUser');
            Route::get('branch_read/{branch_id}/{status?}', [LoanApplicationController::class, 'readByBranchId'])->name('readByBranch');
            Route::get('status/{status}', [LoanApplicationController::class, 'getApplicationsByStatus'])->name('getApplicationsByStatus');
            Route::delete('delete', [LoanApplicationController::class, 'delete'])->name('deleteLoanApplication');
        });

    });
});
