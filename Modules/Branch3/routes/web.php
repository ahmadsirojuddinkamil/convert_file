<?php

use Illuminate\Support\Facades\Route;
use Modules\Branch3\App\Http\Controllers\Branch3Controller;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([], function () {
    Route::resource('branch3', Branch3Controller::class)->names('branch3');
});
