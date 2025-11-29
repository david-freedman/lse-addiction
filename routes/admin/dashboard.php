<?php

use App\Applications\Http\Admin\Dashboard\Controllers\ShowDashboardController;
use App\Applications\Http\Admin\UIKit\Controllers\ShowUIKitController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:admin', 'verified.user'])->group(function () {
    Route::get('/', ShowDashboardController::class)
        ->name('dashboard');

    Route::get('/ui-kit', ShowUIKitController::class)
        ->name('ui-kit');
});
