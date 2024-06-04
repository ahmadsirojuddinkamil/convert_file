<?php

use Illuminate\Support\Facades\Route;
use Modules\Branch2\App\Http\Controllers\Branch2Controller;

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
    Route::resource('branch2', Branch2Controller::class)->names('branch2');
});
