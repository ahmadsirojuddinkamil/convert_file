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

Route::get('/65effc27-af44-4b9b-9f59-dd4152a39555/ba14e920-b536-4790-aaba-c44e42589fa3', function () {
    $targetFolder = base_path().'/storage/app/public';
    $linkFolder = $_SERVER['DOCUMENT_ROOT'].'/storage';

    if (!file_exists($linkFolder)) {
        symlink($targetFolder, $linkFolder);
        return redirect('/');
    }

    return abort(404);
});
