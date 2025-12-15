<?php

use App\Applications\Http\Admin\Webinar\Controllers\CreateWebinarController;
use App\Applications\Http\Admin\Webinar\Controllers\DeleteWebinarController;
use App\Applications\Http\Admin\Webinar\Controllers\EditWebinarController;
use App\Applications\Http\Admin\Webinar\Controllers\GetWebinarsController;
use App\Applications\Http\Admin\Webinar\Controllers\ShowWebinarController;
use App\Applications\Http\Admin\Webinar\Controllers\StoreWebinarController;
use App\Applications\Http\Admin\Webinar\Controllers\UpdateWebinarController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:admin', 'verified.user', 'role:admin'])->group(function () {
    Route::prefix('webinars')->name('webinars.')->group(function () {
        Route::get('/', GetWebinarsController::class)->name('index');
        Route::get('/create', CreateWebinarController::class)->name('create');
        Route::post('/', StoreWebinarController::class)->name('store');
        Route::get('/{webinar}', ShowWebinarController::class)->name('show');
        Route::get('/{webinar}/edit', EditWebinarController::class)->name('edit');
        Route::patch('/{webinar}', UpdateWebinarController::class)->name('update');
        Route::delete('/{webinar}', DeleteWebinarController::class)->name('destroy');
    });
});
