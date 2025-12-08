<?php

use App\Applications\Http\Admin\Module\Controllers\DeleteModuleController;
use App\Applications\Http\Admin\Module\Controllers\ReorderModulesController;
use App\Applications\Http\Admin\Module\Controllers\StoreModuleController;
use App\Applications\Http\Admin\Module\Controllers\UpdateModuleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:admin', 'verified.user', 'role:admin,teacher'])->group(function () {
    Route::prefix('courses/{course}/modules')->name('modules.')->group(function () {
        Route::post('/', StoreModuleController::class)->name('store');
        Route::patch('/reorder', ReorderModulesController::class)->name('reorder');
        Route::patch('/{module}', UpdateModuleController::class)->name('update');
        Route::delete('/{module}', DeleteModuleController::class)->name('destroy');
    });
});
