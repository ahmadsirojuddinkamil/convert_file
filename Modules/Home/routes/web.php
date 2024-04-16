<?php

use Illuminate\Support\Facades\Route;
use Modules\Home\App\Http\Controllers\DeleteConvertController;
use Modules\Home\App\Http\Controllers\HomeController;

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index');
});

Route::controller(DeleteConvertController::class)->group(function () {
    Route::delete('/delete-convert/{save_uuid_from_call}/{save_name_from_call}', 'deleteConvert');
    Route::delete('/delete-convert/10-minute', 'deleteConvert10Minute');
});

Route::get('/storage-link/ba14e920-b536-4790-aaba-c44e42589fa3', function () {
    $targetFolder = base_path().'/storage/app/public';
    $linkFolder = $_SERVER['DOCUMENT_ROOT'].'/storage';
    symlink($targetFolder, $linkFolder);
});
