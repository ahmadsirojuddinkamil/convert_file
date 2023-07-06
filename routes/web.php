<?php

use App\Http\Controllers\pdf_to_word_controller;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home.index');
});

Route::get('/about', function () {
    return view('pages.about.index');
});

Route::controller(pdf_to_word_controller::class)->group(function () {
    Route::get('/pdf_to_word', 'index');
    Route::get('/generate-docx', 'generateDocx');
    Route::post('/pdf_to_word', 'create_convert');
});
