<?php

namespace Tests\Feature;

use App\Services\ValidationService;
use Tests\TestCase;

class ValidationServiceTest extends TestCase
{
    public function test_validate_uuid_success(): void
    {
        $validatedData = new ValidationService();
        $result = $validatedData->validationUuid('a92e209d-cdd9-4315-8ffe-9103ed8ea5ac');
        $this->assertNull($result);
    }

    public function test_validate_uuid_failed_because_not_uuid(): void
    {
        $validatedData = new ValidationService();
        $result = $validatedData->validationUuid('uuid');
        $this->assertFalse($result);
    }
}
