<?php

namespace Modules\Monitoring\tests\Feature;

use App\Services\LoggingService;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class GetDataLoggingTest extends TestCase
{
    protected $logging;

    public function setUp(): void
    {
        parent::setUp();
        $this->logging = new LoggingService();
    }

    public function test_get_data_logging_success(): void
    {
        Log::info('testing data logging');

        $response = $this->get('/logging/0191f719-243e-4f56-bb80-a4d199c282b4');
        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertEquals('success get data logging!', $responseData['message']);

        $this->assertArrayHasKey('log_details', $responseData);
        $logDetails = $responseData['log_details'];
        $this->assertArrayHasKey('testing', $logDetails);

        $result = $this->logging->removeLogTesting();
        $this->assertEquals('Log testing success deleted!', $result);
    }

    public function test_get_data_logging_failed_because_not_uuid(): void
    {
        $response = $this->get('/logging/uuid');
        $response->assertStatus(404);
    }

    public function test_get_data_logging_failed_because_password_not_found(): void
    {
        $response = $this->get('/logging/c8956131-5f9b-4ebd-99f3-9612089f1567');
        $response->assertStatus(404);
    }
}
