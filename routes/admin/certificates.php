<?php

use App\Applications\Http\Admin\Certificate\Controllers\DownloadCertificateController;
use App\Applications\Http\Admin\Certificate\Controllers\GetCertificatesController;
use App\Applications\Http\Admin\Certificate\Controllers\GetPendingCertificatesController;
use App\Applications\Http\Admin\Certificate\Controllers\IssueCertificateController;
use App\Applications\Http\Admin\Certificate\Controllers\PreviewCertificateController;
use App\Applications\Http\Admin\Certificate\Controllers\PublishCertificatesController;
use App\Applications\Http\Admin\Certificate\Controllers\RestoreCertificateController;
use App\Applications\Http\Admin\Certificate\Controllers\RevokeCertificateController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:admin', 'verified.user', 'role:admin,teacher'])->group(function () {
    Route::prefix('certificates')->name('certificates.')->group(function () {
        Route::get('/', GetCertificatesController::class)->name('index');
        Route::get('/pending', GetPendingCertificatesController::class)->name('pending');
        Route::get('/{certificate}/download', DownloadCertificateController::class)->name('download');
        Route::get('/{certificate}/preview', PreviewCertificateController::class)->name('preview');
        Route::post('/publish', PublishCertificatesController::class)->name('publish');
    });
});

Route::middleware(['auth:admin', 'verified.user', 'role:admin'])->group(function () {
    Route::prefix('certificates')->name('certificates.')->group(function () {
        Route::delete('/{certificate}', RevokeCertificateController::class)->name('revoke');
        Route::post('/{certificate}/restore', RestoreCertificateController::class)->name('restore');
    });

    Route::post('/students/{student}/certificates', IssueCertificateController::class)
        ->name('students.certificates.issue');
});
