<?php

namespace Modules\Monitoring\tests\Feature\Service;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Monitoring\App\Services\LoggingMonitorService;
use Tests\TestCase;

class LoggingServiceMonitoringTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_file_log_success(): void
    {
        $loggingService = new LoggingMonitorService();
        $result = $loggingService->getFileLog();

        $this->assertNotNull($result);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('logContent', $result);
        $this->assertArrayHasKey('logType', $result);
        $this->assertArrayHasKey('logMethods', $result);
        $this->assertArrayHasKey('logDetails', $result);
        $this->assertIsArray($result['logContent']);
        $this->assertIsArray($result['logType']);
        $this->assertIsArray($result['logMethods']);
        $this->assertIsArray($result['logDetails']);
    }
}
