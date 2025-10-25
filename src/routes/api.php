<?php

use Illuminate\Support\Facades\Route;
use ON\LaravelClient\Http\Controllers\ONController;

Route::prefix('on')->name('on.')->group(function () {
    Route::get('/health', [ONController::class, 'health'])->name('health');
    Route::get('/site-info', [ONController::class, 'siteInfo'])->name('site-info');
    Route::get('/package-status/{package}', [ONController::class, 'packageStatus'])->name('package-status');
});