<?php

namespace Modules\Monitoring\tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Monitoring\App\Models\UserMonitoring;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class RegisterMonitoringTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_user_monitoring_success(): void
    {
        $requestData = [
            'name' => 'user',
            'email' => 'user@gmail.com',
            'password' => 'password',
        ];

        $response = $this->withHeaders([
            'Authorization' => env('USER_MONITORING'),
            'Accept' => 'application/json',
        ])->post('/api/register-monitoring/f886f5784719298c4c8599851155f67c26090c4c32a5c0d57f18ee20cbf44399', $requestData);

        $response->assertStatus(200);

        $responseData = $response->json();

        $this->assertEquals('success register user monitoring!', $responseData['message']);
        $this->assertEquals($requestData['name'], $responseData['user']['name']);
        $this->assertEquals($requestData['email'], $responseData['user']['email']);
    }

    public function test_register_user_monitoring_failed_because_form_is_empty(): void
    {
        $requestData = [
            'name' => '',
            'email' => '',
            'password' => '',
        ];

        $response = $this->withHeaders([
            'Authorization' => env('USER_MONITORING'),
            'Accept' => 'application/json',
        ])->post('/api/register-monitoring/f886f5784719298c4c8599851155f67c26090c4c32a5c0d57f18ee20cbf44399', $requestData);

        $response->assertStatus(422);
        $responseData = $response->json();

        $this->assertArrayHasKey('errors', $responseData);
        $errors = $responseData['errors'];

        $this->assertArrayHasKey('name', $errors);
        $this->assertEquals(['The name field is required.'], $errors['name']);

        $this->assertArrayHasKey('email', $errors);
        $this->assertEquals(['The email field is required.'], $errors['email']);

        $this->assertArrayHasKey('password', $errors);
        $this->assertEquals(['The password field is required.'], $errors['password']);
    }

    public function test_register_user_monitoring_failed_because_form_is_invalid(): void
    {
        $requestData = [
            'name' => 'user',
            'email' => 'user',
            'password' => 'password',
        ];

        $response = $this->withHeaders([
            'Authorization' => env('USER_MONITORING'),
            'Accept' => 'application/json',
        ])->post('/api/register-monitoring/f886f5784719298c4c8599851155f67c26090c4c32a5c0d57f18ee20cbf44399', $requestData);

        $response->assertStatus(422);
        $responseData = $response->json();

        $this->assertArrayHasKey('errors', $responseData);
        $errors = $responseData['errors'];

        $this->assertArrayHasKey('email', $errors);
        $this->assertEquals(['The email field must be a valid email address.'], $errors['email']);
    }

    public function test_register_user_monitoring_failed_because_user_already_exists(): void
    {
        UserMonitoring::create([
            'uuid' => Uuid::uuid4(),
            'name' => 'user',
            'email' => 'user@gmail.com',
            'password' => 'password',
        ]);

        $requestData = [
            'name' => 'user',
            'email' => 'user@gmail.com',
            'password' => 'password',
        ];

        $response = $this->withHeaders([
            'Authorization' => env('USER_MONITORING'),
            'Accept' => 'application/json',
        ])->post('/api/register-monitoring/f886f5784719298c4c8599851155f67c26090c4c32a5c0d57f18ee20cbf44399', $requestData);

        $response->assertStatus(409);
        $response->assertJson(['message' => 'user already in use!']);
    }
}
