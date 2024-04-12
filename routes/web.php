<?php

use App\Http\Controllers\{commentController,  jpgToPdfController, pdfToJpgController, pdfToPngController,  pngToPdfController};
use Illuminate\Support\Facades\Route;

Route::get('/test-api', function () {
    $data = [
        'message' => 'Ini adalah contoh data JSON laravel',
        'status' => 'OK'
    ];

    return response()->json($data, 200);
});

// Route::controller(commentController::class)->group(function () {
//     Route::post('/create_comment', 'create');
//     Route::post('/create_comment/{save_uuid_reply_from_click}/reply', 'reply');
// });
