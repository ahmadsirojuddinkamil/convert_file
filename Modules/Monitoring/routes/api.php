<?php

use Illuminate\Support\Facades\Route;
use Modules\Monitoring\App\Http\Controllers\LoggingController;

Route::controller(LoggingController::class)->group(function () {
    Route::get('/logging/{save_password_from_call}', 'getDataLogging');
});
