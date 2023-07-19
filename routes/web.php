<?php

use App\Http\Controllers\homeController;
use App\Http\Controllers\JpgToPngController;
use App\Http\Controllers\pngToJpgController;
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
    Route::get('/jpg_to_png/{save_uuid_download_from_click}/download', 'download');
});

Route::controller(pngToJpgController::class)->group(function () {
    Route::get('/png_to_jpg', 'index');
    Route::get('/png_to_jpg/{save_uuid_show_from_click}/file', 'show');
    Route::post('/png_to_jpg', 'create');
    Route::get('/png_to_jpg/{save_uuid_download_from_click}/download', 'download');
});
