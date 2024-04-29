<?php

use Illuminate\Support\Facades\Route;
use Modules\Monitoring\App\Http\Controllers\LoggingController;
use Modules\Monitoring\App\Http\Controllers\StorageLinkController;

Route::controller(StorageLinkController::class)->group(function () {
    Route::get('/generate-storage-link/52951bda-a472-47d8-8f83-d2ca858af657', 'generateStorageLink');
});

Route::controller(LoggingController::class)->group(function () {
    Route::get('/logging/{save_password_from_call}', 'getDataLogging');
});
