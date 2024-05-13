<?php

namespace Modules\Monitoring\tests\Feature\Service;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Monitoring\App\Services\ValidationMonitorService;
use Tests\TestCase;

class ValidationServiceMonitoringTest extends TestCase
{
    use RefreshDatabase;

    public function test_validation_log_type_success(): void
    {
        $data = ['type' => 'example_type'];
        $validationService = new ValidationMonitorService();
        $validator = $validationService->validationLogType(collect($data));
        $this->assertFalse($validator->fails());
        $this->assertEmpty($validator->errors()->all());
    }

    public function test_validation_log_time_success(): void
    {
        $data = [
            'type' => 'example_type',
            'time_start' => '2024-05-09 12:00:00',
            'time_end' => '2024-05-09 13:00:00',
        ];

        $validationService = new ValidationMonitorService();
        $validator = $validationService->validationLogTime(collect($data));
        $this->assertFalse($validator->fails());
        $this->assertEmpty($validator->errors()->all());
    }

    public function test_validation_register_success(): void
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ];

        $validationService = new ValidationMonitorService();
        $validator = $validationService->validationRegister(collect($data));
        $this->assertFalse($validator->fails());
        $this->assertEmpty($validator->errors()->all());
    }

    public function test_validation_login_success(): void
    {
        $data = [
            'email' => 'john@example.com',
            'password' => 'password123',
        ];

        $validationService = new ValidationMonitorService();
        $validator = $validationService->validationLogin(collect($data));
        $this->assertFalse($validator->fails());
        $this->assertEmpty($validator->errors()->all());
    }
}
