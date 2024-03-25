<?php

use Illuminate\Support\Facades\Route;
use Modules\Jpg\App\Http\Controllers\JpgToPdfController;
use Modules\Jpg\App\Http\Controllers\JpgToPngController;

Route::controller(JpgToPngController::class)->group(function () {
    Route::get('/jpg-to-png', 'index');
    Route::post('/jpg-to-png', 'create');
    Route::get('/jpg-to-png/{save_uuid_from_event}', 'show');
    Route::post('/jpg-to-png/{save_uuid_reply_from_click}', 'reply');
    Route::get('/jpg-to-png/{save_uuid_from_event}/download', 'download');
});

Route::controller(JpgToPdfController::class)->group(function () {
    Route::get('/jpg-to-pdf', 'index');
    Route::post('/jpg-to-pdf', 'create');
    Route::get('/jpg-to-pdf/{save_uuid_show_from_click}', 'show');
    Route::post('/jpg-to-pdf/{save_uuid_reply_from_click}', 'reply');
    Route::get('/jpg-to-pdf/{save_uuid_download_from_click}/download', 'download');
});
