<?php

use Modules\Monitoring\App\Http\Controllers\{LoggingController, StorageLinkController, UserMonitoringController};
use Modules\Monitoring\App\Http\Middleware\{JwtMiddleware, UserMiddleware};
use Illuminate\Support\Facades\Route;

Route::middleware(UserMiddleware::class)->controller(StorageLinkController::class)->group(function () {
    Route::get('/generate-storage-link/96943e2168ae2e273660181356e9aaaa13ecf99062d8f36790ea63bf087e07f1', 'generateStorageLink');
});

Route::middleware(JwtMiddleware::class)->controller(LoggingController::class)->group(function () {
    Route::post('/logging/ca6d3d44ffd92e779b88718d1f73f780e93cd668d2b56e30b83726c79a1bd096', 'getDataLogging');
    Route::post('/logging/d22b028acdaf47120dd442bd30fb5b6edaeb81cede74626df0ef15f80d149399/type', 'getDataLoggingByType');
    Route::post('/logging/be69cfcc5b843bc9d004d88d2ad228a8a7296f35bfd781a23acafdc310ce9df8/type/time', 'getDataLoggingByTime');
    Route::delete('/logging/7d381b67839858e95e98e6e941eda289305fe63779446ec6e6445f3c29dc0d8d', 'deleteDataLogging');
    Route::delete('/logging/db8bd44795d51da10dc913d656ccb0d5a24126b7254b62a14066e180a04c7e7c/type', 'deleteDataLoggingByType');
    Route::delete('/logging/c090f298112c5c2579292e7f64a501aae003cde61601a6c5f4c0e25325add730/type/time', 'deleteDataLoggingByTime');
});

Route::middleware(UserMiddleware::class)->controller(UserMonitoringController::class)->group(function () {
    Route::post('/register-monitoring/f886f5784719298c4c8599851155f67c26090c4c32a5c0d57f18ee20cbf44399', 'register');
    Route::post('/login-monitoring/1868304912fabaa7f75db8f254b7a65d8ce0cfcbda70adcd3a2b7d3ed9f03eae', 'login');
});
