<?php

namespace Modules\Monitoring\tests\Feature\Logging;

use Modules\Utility\App\Services\LoggingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Modules\Monitoring\App\Services\JwtMonitorService;
use Tests\TestCase;

class GetLoggingTest extends TestCase
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

    public function test_get_data_logging_success(): void
    {
        $tokenJwt = $this->jwtService->generateTokenJwt();

        Log::info('testing data');

        $response = $this->post('api/logging/ca6d3d44ffd92e779b88718d1f73f780e93cd668d2b56e30b83726c79a1bd096', [], [
            'Authorization' => $tokenJwt
        ]);
        $response->assertStatus(200);

        $logging = $response->json();
        $this->assertIsArray($logging);
        $this->assertEquals('success get data logging!', $logging['message']);

        $this->assertArrayHasKey('log_details', $logging);
        $this->assertIsArray($logging['log_details']);
        $this->assertNotEmpty($logging['log_details']);

        $result = $this->logging->removeLogTesting();
        $this->assertEquals('Log testing success deleted!', $result);
    }

    public function test_get_data_logging_failed_because_log_is_empty(): void
    {
        $tokenJwt = $this->jwtService->generateTokenJwt();

        $response = $this->post('api/logging/ca6d3d44ffd92e779b88718d1f73f780e93cd668d2b56e30b83726c79a1bd096', [], [
            'Authorization' => $tokenJwt
        ]);
        $response->assertStatus(204);
        $this->assertEquals('data logging not found!', $response->original['message']);
    }
}
