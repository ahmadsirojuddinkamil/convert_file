<?php

namespace Modules\Monitoring\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ValidationService;
use Illuminate\Support\Facades\Hash;

class LoggingController extends Controller
{
    protected $validationService;

    public function __construct(ValidationService $validationService)
    {
        $this->validationService = $validationService;
    }

    public function getDataLogging($save_password_from_call)
    {
        $validator = $this->validationService->validationPassword($save_password_from_call);

        if ($validator === false) {
            return abort(404);
        }

        $salt = env('LOGGING_SALT');
        $hashed = env('LOGGING_RESULT');

        if (Hash::check($save_password_from_call . $salt, $hashed)) {
            $logFile = storage_path('logs/laravel.log');
            $logContent = file($logFile);

            $logType = [
                'local',
                'testing',
                'production'
            ];

            $logMethods = ['info', 'emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'debug'];
            $logDetails = [];

            foreach ($logContent as $logLine) {
                foreach ($logType as $type) {
                    if (strpos($logLine, $type) !== false) {
                        if (!isset($logDetails[$type])) {
                            $logDetails[$type] = [];
                        }

                        foreach ($logMethods as $method) {
                            if (strpos($logLine, strtoupper($method)) !== false) {
                                $logDetails[$type][$method][] = $logLine;
                            }
                        }
                    }
                }
            }

            return response()->json(['message' => 'success get data logging!', 'log_details' => $logDetails], 200);
        }

        return abort(404);
    }
}
