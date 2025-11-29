<?php

use App\Applications\Http\Student\Catalog\Controllers\GetCatalogController;
use App\Applications\Http\Student\Catalog\Controllers\PurchaseCourseController;
use App\Applications\Http\Student\Catalog\Controllers\ShowCatalogCourseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified.student'])->group(function () {
    Route::get('catalog', GetCatalogController::class)->name('catalog.index');
    Route::get('catalog/{course}', ShowCatalogCourseController::class)->name('catalog.show');
    Route::post('catalog/{course}/purchase', PurchaseCourseController::class)->name('catalog.purchase');
});
