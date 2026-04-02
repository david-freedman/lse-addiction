<?php

use App\Applications\Http\Admin\Specialty\Controllers\CreateSpecialtyController;
use App\Applications\Http\Admin\Specialty\Controllers\DeleteSpecialtyController;
use App\Applications\Http\Admin\Specialty\Controllers\EditSpecialtyController;
use App\Applications\Http\Admin\Specialty\Controllers\GetSpecialtiesController;
use App\Applications\Http\Admin\Specialty\Controllers\StoreSpecialtyController;
use App\Applications\Http\Admin\Specialty\Controllers\UpdateSpecialtyController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:admin', 'verified.user', 'role:admin'])->group(function () {
    Route::prefix('specialties')->name('specialties.')->group(function () {
        Route::get('/', GetSpecialtiesController::class)->name('index');
        Route::get('/create', CreateSpecialtyController::class)->name('create');
        Route::post('/', StoreSpecialtyController::class)->name('store');
        Route::get('/{specialty}/edit', EditSpecialtyController::class)->name('edit');
        Route::patch('/{specialty}', UpdateSpecialtyController::class)->name('update');
        Route::delete('/{specialty}', DeleteSpecialtyController::class)->name('destroy');
    });
});
