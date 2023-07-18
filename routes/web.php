<?php

use App\Http\Controllers\JpgToPngController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home.index');
});

Route::get('/about', function () {
    return view('pages.about.index');
});

Route::controller(JpgToPngController::class)->group(function () {
    Route::get('/jpg_to_png', 'index');
    Route::get('/jpg_to_png/{save_uuid_show_from_click}/file', 'show');
    Route::post('/jpg_to_png', 'create');
    Route::get('/jpg_to_png/{save_uuid_download_from_click}/download', 'download');
    Route::delete('/jpg_to_png/{save_uuid_delete_from_axios}', 'delete');
});
