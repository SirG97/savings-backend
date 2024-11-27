<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->middleware(['check.suspended'])->group(function () {
    Route::middleware(['check.email.verification', 'check.default.password', 'check.suspended'])->group(function () {
        Route::prefix('users')->group(function () {
            Route::post('create', [UserController::class, 'create'])->name('createUser');
            Route::delete('delete', [UserController::class, 'delete'])->name('deleteUser');
            Route::get('read/{id?}', [UserController::class, 'read'])->name('readUser');
            Route::get('model_read/{model}/{id?}', [UserController::class, 'readByUserModel'])->name('readByUserModel');
            Route::put('update', [UserController::class, 'update'])->name('updateUser');
            Route::put('suspend', [UserController::class, 'suspend'])->name('suspendUser');

        });
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
    });
});
