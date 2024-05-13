<?php

namespace Modules\Utility\App\Services;

class TimeService
{
    public function startCalculateProcessTime()
    {
        $startTime = microtime(true);

        return $startTime;
    }

    public function endCalculateProcessTime($saveStartTimeFromCall)
    {
        $startTime = $saveStartTimeFromCall;
        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;
        $roundedExecutionTime = round($executionTime, 2);

        return $roundedExecutionTime;
    }
}
