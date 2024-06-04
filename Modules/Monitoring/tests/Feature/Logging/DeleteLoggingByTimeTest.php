<?php

namespace Modules\Monitoring\tests\Feature\Logging;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Modules\Utility\App\Services\LoggingService;
use Tests\TestCase;
use Carbon\Carbon;
use Modules\Monitoring\App\Services\JwtMonitorService;

class DeleteLoggingByTimeTest extends TestCase
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

    public function test_delete_data_logging_by_time_success(): void
    {
        $tokenJwt = $this->jwtService->generateTokenJwt();

        Log::info('testing data');

        $now = Carbon::now();
        $timeStart = $now->copy()->subMinute()->toDateTimeString();
        $timeEnd = $now->copy()->addMinutes(2)->toDateTimeString();

        $response = $this->delete('api/logging/c090f298112c5c2579292e7f64a501aae003cde61601a6c5f4c0e25325add730/type/time', [
            'type' => 'testing',
            'time_start' => $timeStart,
            'time_end' => $timeEnd,
        ], [
            'Authorization' => $tokenJwt
        ]);
        $response->assertStatus(200);

        $logging = $response->json();

        $this->assertIsArray($logging);
        $this->assertEquals("Success delete data logging by type: testing. Start time: $timeStart. End time: $timeEnd", $logging['message']);

        $this->assertArrayHasKey('log_details', $logging);
        $this->assertNotEmpty($logging['log_details']);
    }

    public function test_delete_data_logging_by_time_failed_because_log_is_empty(): void
    {
        $tokenJwt = $this->jwtService->generateTokenJwt();

        $now = Carbon::now();
        $timeStart = $now->copy()->subMinute()->toDateTimeString();
        $timeEnd = $now->copy()->addMinutes(2)->toDateTimeString();

        $response = $this->delete('api/logging/c090f298112c5c2579292e7f64a501aae003cde61601a6c5f4c0e25325add730/type/time', [
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
