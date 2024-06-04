<?php

namespace Modules\Monitoring\tests\Feature\Logging;

use Modules\Utility\App\Services\LoggingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Modules\Monitoring\App\Services\JwtMonitorService;
use Tests\TestCase;

class DeleteLoggingTest extends TestCase
{
    use RefreshDatabase;

    protected $jwtService;
    protected $logging;

    public function setUp(): void
    {
        parent::setUp();
        $this->jwtService = new JwtMonitorService();
        $this->logging = new LoggingService();
    }

    public function test_delete_data_logging_success(): void
    {
        $tokenJwt = $this->jwtService->generateTokenJwt();

        Log::info('testing data');

        $response = $this->delete('api/logging/7d381b67839858e95e98e6e941eda289305fe63779446ec6e6445f3c29dc0d8d', [], [
            'Authorization' => $tokenJwt
        ]);
        $response->assertStatus(200);

        $logging = $response->json();

        $this->assertIsArray($logging);
        $this->assertEquals('All data in log deleted successfully.', $logging['message']);

        $this->assertArrayHasKey('log_details', $logging);
        $this->assertNotEmpty($logging['log_details']);
    }

    public function test_delete_data_logging_failed_because_log_is_empty(): void
    {
        $tokenJwt = $this->jwtService->generateTokenJwt();

        $response = $this->delete('api/logging/7d381b67839858e95e98e6e941eda289305fe63779446ec6e6445f3c29dc0d8d', [], [
            'Authorization' => $tokenJwt
        ]);
        $response->assertStatus(204);
        $this->assertEquals('data logging not found!', $response->original['message']);
    }
}
