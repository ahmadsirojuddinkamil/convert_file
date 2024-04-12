<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/test-api-data', function () {
    $data = [
        'message' => 'Ini adalah contoh data JSON laravel api',
        'status' => 'OK'
    ];

    // Mengembalikan respons dengan status 200 dan data dalam bentuk JSON
    return response()->json($data, 200);
});
