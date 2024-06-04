<?php

namespace Modules\Utility\tests\Feature;

use Modules\Utility\App\Services\TimeService;
use Tests\TestCase;

class TimeServiceTest extends TestCase
{
    public function testStartCalculateProcessTime(): void
    {
        $example = new TimeService();
        $startTime = $example->startCalculateProcessTime();
        $this->assertIsFloat($startTime);
    }

    public function testEndCalculateProcessTime()
    {
        $example = new TimeService();
        $startTime = microtime(true);
        $executionTime = $example->endCalculateProcessTime($startTime);
        $this->assertIsFloat($executionTime);
    }
}
