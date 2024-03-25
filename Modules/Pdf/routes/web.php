<?php

use Illuminate\Support\Facades\Route;
use Modules\Pdf\App\Http\Controllers\{PdfToJpgController, PdfToPngController};

Route::controller(PdfToJpgController::class)->group(function () {
    Route::get('/pdf-to-jpg', 'index');
    Route::post('/pdf-to-jpg', 'create');
    Route::get('/pdf-to-jpg/{save_uuid_show_from_click}', 'show');
    Route::post('/pdf-to-jpg/{save_uuid_reply_from_click}', 'reply');
    Route::get('/pdf-to-jpg/{save_uuid_download_from_click}/download', 'download');
});

Route::controller(PdfToPngController::class)->group(function () {
    Route::get('/pdf-to-png', 'index');
    Route::post('/pdf-to-png', 'create');
    Route::get('/pdf-to-png/{save_uuid_show_from_click}', 'show');
    Route::post('/pdf-to-png/{save_uuid_reply_from_click}', 'reply');
    Route::get('/pdf-to-png/{save_uuid_download_from_click}/download', 'download');
});
