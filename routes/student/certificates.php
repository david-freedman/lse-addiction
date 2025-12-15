<?php

use App\Applications\Http\Student\Certificate\Controllers\DownloadCertificateController;
use App\Applications\Http\Student\Certificate\Controllers\GetCertificatesController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified.student'])->group(function () {
    Route::get('certificates', GetCertificatesController::class)->name('certificates');
    Route::get('certificates/{certificate}/download', DownloadCertificateController::class)
        ->name('certificates.download');
});
