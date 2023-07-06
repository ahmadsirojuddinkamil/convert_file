<?php

use App\Http\Controllers\jpg_to_png_controller;
use App\Http\Controllers\word_to_pdf_controller;
use Illuminate\Support\Facades\Route;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('pages.home.index');
});

Route::get('/about', function () {
    return view('pages.about.index');
});

Route::controller(jpg_to_png_controller::class)->group(function () {
    Route::get('/jpg_to_png', 'index');
    // Route::post('/jpg_to_png', 'create');
    Route::get('/jpg_to_png/create', 'create');
});
