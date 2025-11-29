<?php

use App\Applications\Http\Admin\User\Controllers\CreateUserController;
use App\Applications\Http\Admin\User\Controllers\DeleteUserController;
use App\Applications\Http\Admin\User\Controllers\EditUserController;
use App\Applications\Http\Admin\User\Controllers\GetUsersController;
use App\Applications\Http\Admin\User\Controllers\ShowUserController;
use App\Applications\Http\Admin\User\Controllers\StoreUserController;
use App\Applications\Http\Admin\User\Controllers\ToggleUserStatusController;
use App\Applications\Http\Admin\User\Controllers\UpdateUserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:admin', 'verified.user', 'role:admin'])->group(function () {
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', GetUsersController::class)->name('index');
        Route::get('/create', CreateUserController::class)->name('create');
        Route::post('/', StoreUserController::class)->name('store');
        Route::get('/{user}', ShowUserController::class)->name('show');
        Route::get('/{user}/edit', EditUserController::class)->name('edit');
        Route::patch('/{user}', UpdateUserController::class)->name('update');
        Route::delete('/{user}', DeleteUserController::class)->name('delete');
        Route::patch('/{user}/toggle-status', ToggleUserStatusController::class)->name('toggle-status');
    });
});
