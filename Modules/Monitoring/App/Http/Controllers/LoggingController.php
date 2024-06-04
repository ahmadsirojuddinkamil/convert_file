<?php

namespace Modules\Monitoring\App\Http\Controllers;

use Modules\Monitoring\App\Services\{LoggingMonitorService, ValidationMonitorService};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LoggingController extends Controller
{
    protected $validationMonitorService;
    protected $loggingMonitorService;

    public function __construct(ValidationMonitorService $validationMonitorService, LoggingMonitorService $loggingMonitorService)
    {
        $this->validationMonitorService = $validationMonitorService;
        $this->loggingMonitorService = $loggingMonitorService;
    }

    public function getDataLogging()
    {
        $file = $this->loggingMonitorService->getFileLog();

        foreach ($file['logContent'] as $logLine) {
            foreach ($file['logType'] as $type) {
                if (strpos($logLine, $type) !== false) {
                    if (!isset($logDetails[$type])) {
                        $logDetails[$type] = [];
                    }

                    foreach ($file['logMethods'] as $method) {
                        if (strpos($logLine, strtoupper($method)) !== false) {
                            $logDetails[$type][$method][] = $logLine;
                        }
                    }
                }
            }
        }

        if (empty($logDetails)) {
            return response()->json(['message' => 'data logging not found!'], 204);
        }

        return response()->json(['message' => 'success get data logging!', 'log_details' => $logDetails], 200);
    }

    public function getDataLoggingByType(Request $request)
    {
        $validator = $this->validationMonitorService->validationLogType($request);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $file = $this->loggingMonitorService->getFileLog();

        foreach ($file['logContent'] as $logLine) {
            foreach ($file['logType'] as $type) {
                if (strpos($logLine, $type) !== false && $type === $request->type) {
                    if (!isset($logDetails[$type])) {
                        $logDetails[$type] = [];
                    }

                    foreach ($file['logMethods'] as $method) {
                        if (strpos($logLine, strtoupper($method)) !== false) {
                            $logDetails[$type][$method][] = $logLine;
                        }
                    }
                }
            }
        }

        if (empty($logDetails)) {
            return response()->json(['message' => 'data logging not found!'], 204);
        }

        return response()->json(['message' => 'success get data logging!', 'log_details' => $logDetails], 200);
    }

    public function getDataLoggingByTime(Request $request)
    {
        $validator = $this->validationMonitorService->validationLogTime($request);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $file = $this->loggingMonitorService->getFileLog();

        $startTime = Carbon::parse($request->time_start);
        $endTime = Carbon::parse($request->time_end);

        foreach ($file['logContent'] as $logLine) {
            foreach ($file['logType'] as $type) {
                if (strpos($logLine, $type) !== false && $type === $request->type) {
                    if (!isset($logDetails[$type])) {
                        $logDetails[$type] = [];
                    }

                    $logTime = $this->extractLogTime($logLine);

                    if ($logTime->greaterThanOrEqualTo($startTime) && $logTime->lessThanOrEqualTo($endTime)) {
                        foreach ($file['logMethods'] as $method) {
                            if (strpos($logLine, strtoupper($method)) !== false) {
                                $logDetails[$type][$method][] = $logLine;
                            }
                        }
                    }
                }
            }
        }

        if (empty($logDetails)) {
            return response()->json(['message' => 'data logging not found!'], 204);
        }

        return response()->json(['message' => 'success get data logging!', 'log_details' => $logDetails], 200);
    }

    public function deleteDataLogging()
    {
        $logFile = storage_path('logs/laravel.log');

        if (!file_exists($logFile)) {
            return response()->json(['message' => 'file logging not found!'], 404);
        }

        $logDetails = file_get_contents($logFile);

        if (empty($logDetails)) {
            return response()->json(['message' => 'data logging not found!'], 204);
        }

        file_put_contents($logFile, '');
        return response()->json(['message' => 'All data in log deleted successfully.', 'log_details' => $logDetails], 200);
    }

    public function deleteDataLoggingByType(Request $request)
    {
        $validator = $this->validationMonitorService->validationLogType($request);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $logType = $request->type;
        $logFile = storage_path('logs/laravel.log');

        if (!file_exists($logFile)) {
            return response()->json(['message' => 'file logging not found!'], 404);
        }

        $logContent = file_get_contents($logFile);

        $logDetails = [];
        $newLogContent = preg_replace("/.*?$logType.*?(\r?\n|$)/", '', $logContent, -1, $count);

        if ($count > 0) {
            preg_match_all("/.*?$logType.*?(\r?\n|$)/", $logContent, $matches);
            $logDetails = $matches[0];
            file_put_contents($logFile, $newLogContent);
            return response()->json(['message' => "success delete data logging by type: $logType", 'log_details' => $logDetails], 200);
        }

        return response()->json(['message' => 'data logging not found!'], 204);
    }

    public function deleteDataLoggingByTime(Request $request)
    {
        $validator = $this->validationMonitorService->validationLogTime($request);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $logFile = storage_path('logs/laravel.log');
        $logContent = file($logFile);

        $startTime = Carbon::parse($request->time_start);
        $endTime = Carbon::parse($request->time_end);

        $log_details = array_filter($logContent, function ($logLine) use ($request, $startTime, $endTime) {
            if (strpos($logLine, $request->type) !== false) {
                $logTime = $this->extractLogTime($logLine);
                return $logTime->greaterThanOrEqualTo($startTime) && $logTime->lessThanOrEqualTo($endTime);
            }
            return false;
        });

        $logContent = array_diff($logContent, $log_details);
        file_put_contents($logFile, implode('', $logContent));

        if (empty($log_details)) {
            return response()->json(['message' => 'data logging not found!'], 204);
        }

        $message = sprintf(
            'Success delete data logging by type: %s. Start time: %s. End time: %s',
            $request->type,
            $startTime,
            $endTime
        );

        return response()->json([
            'message' => $message,
            'log_details' => $log_details
        ], 200);
    }

    private function extractLogTime($logLine)
    {
        preg_match('/^\[(.*?)\]/', $logLine, $matches);
        return Carbon::parse($matches[1]);
    }
}
