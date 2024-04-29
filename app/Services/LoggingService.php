<?php

namespace App\Services;

class LoggingService
{
    public function removeLogTesting()
    {
        $logPath = storage_path('logs/laravel.log');
        $logLines = file($logPath);

        $filteredLogLines = [];
        foreach ($logLines as $line) {
            if (strpos($line, 'testing') === false) {
                $filteredLogLines[] = $line;
            }
        }

        $newLogContent = implode('', $filteredLogLines);
        file_put_contents($logPath, $newLogContent);

        $initialLineCount = count($logLines);
        $finalLineCount = count($filteredLogLines);
        $deletedLineCount = $initialLineCount - $finalLineCount;

        if ($deletedLineCount != 0) {
            return "Log testing success deleted!";
        }

        return "Nothing log testing!";
    }
}
