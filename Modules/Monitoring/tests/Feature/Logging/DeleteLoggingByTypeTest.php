<?php

namespace Modules\Monitoring\tests\Feature\Logging;

use Modules\Utility\App\Services\LoggingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Modules\Monitoring\App\Services\JwtMonitorService;
use Tests\TestCase;

class DeleteLoggingByTypeTest extends TestCase
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

    public function test_delete_data_logging_by_type_success(): void
    {
        $tokenJwt = $this->jwtService->generateTokenJwt();

        Log::info('testing data');

        $response = $this->delete('api/logging/db8bd44795d51da10dc913d656ccb0d5a24126b7254b62a14066e180a04c7e7c/type', [
            'type' => 'testing'
        ], [
            'Authorization' => $tokenJwt
        ]);
        $response->assertStatus(200);

        $logging = $response->json();

        $this->assertIsArray($logging);
        $this->assertEquals('success delete data logging by type: testing', $logging['message']);

        $this->assertArrayHasKey('log_details', $logging);
        $this->assertNotEmpty($logging['log_details']);
    }

    public function test_delete_data_logging_by_type_failed_because_log_is_empty(): void
    {
        $tokenJwt = $this->jwtService->generateTokenJwt();

        $response = $this->delete('api/logging/db8bd44795d51da10dc913d656ccb0d5a24126b7254b62a14066e180a04c7e7c/type', [
            'type' => 'testing'
        ], [
            'Authorization' => $tokenJwt
        ]);
        $response->assertStatus(204);
        $this->assertEquals('data logging not found!', $response->original['message']);
    }
}
