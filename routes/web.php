<?php

use App\Http\Controllers\{homeController, jpgToPdfController, JpgToPngController, pdfToJpgController, pngToJpgController};
use Illuminate\Support\Facades\Route;

Route::controller(homeController::class)->group(function () {
    Route::get('/', 'index');
    Route::delete('/delete_all_file/{save_uuid_from_axios}', 'deleteAllFile');
});

Route::get('/about', function () {
    return view('pages.about.index');
});

Route::controller(JpgToPngController::class)->group(function () {
    Route::get('/jpg_to_png', 'index');
    Route::get('/jpg_to_png/{save_uuid_show_from_click}/file', 'show');
    Route::post('/jpg_to_png', 'create');
    Route::post('/jpg_to_png/{save_uuid_reply_from_click}/reply', 'reply');
    Route::get('/jpg_to_png/{save_uuid_download_from_click}/download', 'download');
});

Route::controller(pngToJpgController::class)->group(function () {
    Route::get('/png_to_jpg', 'index');
    Route::get('/png_to_jpg/{save_uuid_show_from_click}/file', 'show');
    Route::post('/png_to_jpg', 'create');
    Route::post('/png_to_jpg/{save_uuid_reply_from_click}/reply', 'reply');
    Route::get('/png_to_jpg/{save_uuid_download_from_click}/download', 'download');
});

Route::controller(jpgToPdfController::class)->group(function () {
    Route::get('/jpg_to_pdf', 'index');
    Route::get('/jpg_to_pdf/{save_uuid_show_from_click}/file', 'show');
    Route::post('/jpg_to_pdf', 'create');
    Route::post('/jpg_to_pdf/{save_uuid_reply_from_click}/reply', 'reply');
    Route::get('/jpg_to_pdf/{save_uuid_download_from_click}/download', 'download');
});

Route::controller(pdfToJpgController::class)->group(function () {
    Route::get('/pdf_to_jpg', 'index');
    Route::get('/pdf_to_jpg/{save_uuid_show_from_click}/file', 'show');
    Route::post('/pdf_to_jpg', 'create');
    Route::post('/pdf_to_jpg/{save_uuid_reply_from_click}/reply', 'reply');
    Route::get('/pdf_to_jpg/{save_uuid_download_from_click}/download', 'download');
});
