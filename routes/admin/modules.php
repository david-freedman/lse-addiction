<?php

use App\Applications\Http\Admin\Module\Controllers\CreateModuleController;
use App\Applications\Http\Admin\Module\Controllers\DeleteModuleController;
use App\Applications\Http\Admin\Module\Controllers\EditModuleController;
use App\Applications\Http\Admin\Module\Controllers\GetModulesController;
use App\Applications\Http\Admin\Module\Controllers\ReorderModulesController;
use App\Applications\Http\Admin\Module\Controllers\StoreModuleController;
use App\Applications\Http\Admin\Module\Controllers\UpdateModuleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:admin', 'verified.user', 'role:admin,teacher'])->group(function () {
    Route::prefix('courses/{course}/modules')->name('modules.')->group(function () {
        Route::get('/', GetModulesController::class)->name('index');
        Route::get('/create', CreateModuleController::class)->name('create');
        Route::post('/', StoreModuleController::class)->name('store');
        Route::patch('/reorder', ReorderModulesController::class)->name('reorder');
        Route::get('/{module}/edit', EditModuleController::class)->name('edit');
        Route::patch('/{module}', UpdateModuleController::class)->name('update');
        Route::delete('/{module}', DeleteModuleController::class)->name('destroy');
    });
});
