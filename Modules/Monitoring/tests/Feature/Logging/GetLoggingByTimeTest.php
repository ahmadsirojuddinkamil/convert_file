<?php

namespace Modules\Monitoring\tests\Feature\Logging;

use Modules\Utility\App\Services\LoggingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Carbon\Carbon;
use Modules\Monitoring\App\Services\JwtMonitorService;

class GetLoggingByTimeTest extends TestCase
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

    public function test_get_data_logging_by_time_success(): void
    {
        $tokenJwt = $this->jwtService->generateTokenJwt();

        Log::info('testing data');

        $now = Carbon::now();
        $timeStart = $now->copy()->subMinute()->toDateTimeString();
        $timeEnd = $now->copy()->addMinutes(2)->toDateTimeString();

        $response = $this->post('api/logging/be69cfcc5b843bc9d004d88d2ad228a8a7296f35bfd781a23acafdc310ce9df8/type/time', [
            'type' => 'testing',
            'time_start' => $timeStart,
            'time_end' => $timeEnd,
        ], [
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

    public function test_get_data_logging_by_time_failed_because_log_is_empty(): void
    {
        $tokenJwt = $this->jwtService->generateTokenJwt();

        $now = Carbon::now();
        $timeStart = $now->copy()->subMinute()->toISOString();
        $timeEnd = $now->copy()->addMinutes(2)->toISOString();

        $response = $this->post('api/logging/d22b028acdaf47120dd442bd30fb5b6edaeb81cede74626df0ef15f80d149399/type', [
            'type' => 'testing',
            'time_start' => $timeStart,
            'time_end' => $timeEnd,
        ], [
            'Authorization' => $tokenJwt
        ]);
        $response->assertStatus(204);
        $this->assertEquals('data logging not found!', $response->original['message']);
    }
}
