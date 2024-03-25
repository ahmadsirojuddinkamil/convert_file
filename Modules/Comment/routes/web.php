<?php

use Illuminate\Support\Facades\Route;
use Modules\Comment\App\Http\Controllers\CommentController;

Route::controller(CommentController::class)->group(function () {
    Route::post('/create-comment', 'create');
});
