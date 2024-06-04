<?php

namespace Modules\Utility\tests\Feature;

use Modules\Utility\App\Services\LoggingService;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class LoggingServiceTest extends TestCase
{
    public function test_remove_log_testing_success(): void
    {
        Log::info('simulation log testing');

        $loggingService = new LoggingService();
        $result = $loggingService->removeLogTesting();
        $this->assertEquals('Log testing success deleted!', $result);
    }

    public function test_remove_log_testing_failed_because_nothing_log(): void
    {
        $loggingService = new LoggingService();
        $result = $loggingService->removeLogTesting();
        $this->assertEquals('Nothing log testing!', $result);
    }
}
