<?php

use Illuminate\Support\Facades\Route;
use Modules\Monitoring\App\Http\Controllers\StorageLinkController;

Route::controller(StorageLinkController::class)->group(function () {
    Route::get('/generate-storage-link/52951bda-a472-47d8-8f83-d2ca858af657', 'generateStorageLink');
});
