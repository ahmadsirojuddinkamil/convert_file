<?php

namespace Modules\Monitoring\tests\Feature\Service;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Monitoring\App\Services\JwtMonitorService;
use Tests\TestCase;

class JwtServiceMonitoringTest extends TestCase
{
    use RefreshDatabase;

    public function test_generate_jwt_token_success(): void
    {
        $jwtService = new JwtMonitorService();
        $generatedToken = $jwtService->generateTokenJwt();
        $this->assertNotEmpty($generatedToken);
    }
}
