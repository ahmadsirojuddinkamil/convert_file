<?php

use Illuminate\Support\Facades\Route;
use Modules\Png\App\Http\Controllers\PngToJpgController;
use Modules\Png\App\Http\Controllers\PngToPdfController;

Route::controller(PngToJpgController::class)->group(function () {
    Route::get('/png-to-jpg', 'index');
    Route::post('/png-to-jpg', 'create');
    Route::get('/png-to-jpg/{save_uuid_show_from_click}', 'show');
    Route::post('/png-to-jpg/{save_uuid_reply_from_click}', 'reply');
    Route::get('/png-to-jpg/{save_uuid_download_from_click}/download', 'download');
});

Route::controller(PngToPdfController::class)->group(function () {
    Route::get('/png-to-pdf', 'index');
    Route::post('/png-to-pdf', 'create');
    Route::get('/png-to-pdf/{save_uuid_show_from_click}', 'show');
    Route::post('/png-to-pdf/{save_uuid_reply_from_click}', 'reply');
    Route::get('/png-to-pdf/{save_uuid_download_from_click}/download', 'download');
});
