<?php

namespace Modules\Monitoring\App\Services;

class LoggingMonitorService
{
    public function getFileLog()
    {
        $logFile = storage_path('logs/laravel.log');

        if (!file_exists($logFile)) {
            return response()->json(['message' => 'file logging not found!'], 404);
        }

        $logContent = file($logFile);

        $logType = [
            'local',
            'testing',
            'production'
        ];

        $logMethods = ['info', 'emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'debug'];
        $logDetails = [];

        return [
            'logContent' => $logContent,
            'logType' => $logType,
            'logMethods' => $logMethods,
            'logDetails' => $logDetails,
        ];
    }
}
