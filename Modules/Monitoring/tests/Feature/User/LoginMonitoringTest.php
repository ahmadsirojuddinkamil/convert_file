<?php

namespace Modules\Monitoring\tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Monitoring\App\Models\UserMonitoring;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\{JWT, Key};
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class LoginMonitoringTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_user_monitoring_success(): void
    {
        $hashedPassword = Hash::make(env('SALT_LOGGING') . 'password');

        UserMonitoring::create([
            'uuid' => Uuid::uuid4(),
            'name' => 'user',
            'email' => 'user@gmail.com',
            'password' => $hashedPassword,
        ]);

        $requestData = [
            'email' => 'user@gmail.com',
            'password' => 'password',
        ];

        $response = $this->withHeaders([
            'Authorization' => env('USER_MONITORING'),
            'Accept' => 'application/json',
        ])->post('/api/login-monitoring/1868304912fabaa7f75db8f254b7a65d8ce0cfcbda70adcd3a2b7d3ed9f03eae', $requestData);

        $response->assertStatus(200);

        $responseData = $response->json();

        $this->assertEquals('Login successful!', $responseData['message']);

        $this->assertArrayHasKey('token', $responseData);
        $decoded = JWT::decode($responseData['token'], new Key(env('JWT_SECRET_MONITORING'), 'HS256'));
        $this->assertEquals($requestData['email'], $decoded->sub);
    }

    public function test_login_user_monitoring_failed_because_form_is_empty(): void
    {
        $requestData = [
            'email' => '',
            'password' => '',
        ];

        $response = $this->withHeaders([
            'Authorization' => env('USER_MONITORING'),
            'Accept' => 'application/json',
        ])->post('/api/login-monitoring/1868304912fabaa7f75db8f254b7a65d8ce0cfcbda70adcd3a2b7d3ed9f03eae', $requestData);

        $response->assertStatus(422);
        $responseData = $response->json();

        $this->assertArrayHasKey('errors', $responseData);
        $errors = $responseData['errors'];

        $this->assertArrayHasKey('email', $errors);
        $this->assertEquals(['The email field is required.'], $errors['email']);

        $this->assertArrayHasKey('password', $errors);
        $this->assertEquals(['The password field is required.'], $errors['password']);
    }

    public function test_login_user_monitoring_failed_because_form_is_invalid(): void
    {
        $requestData = [
            'email' => 'user',
            'password' => 'password',
        ];

        $response = $this->withHeaders([
            'Authorization' => env('USER_MONITORING'),
            'Accept' => 'application/json',
        ])->post('/api/login-monitoring/1868304912fabaa7f75db8f254b7a65d8ce0cfcbda70adcd3a2b7d3ed9f03eae', $requestData);

        $response->assertStatus(422);
        $responseData = $response->json();

        $this->assertArrayHasKey('errors', $responseData);
        $errors = $responseData['errors'];

        $this->assertArrayHasKey('email', $errors);
        $this->assertEquals(['The email field must be a valid email address.'], $errors['email']);
    }

    public function test_login_user_monitoring_failed_because_user_not_found(): void
    {
        $requestData = [
            'email' => 'user@gmail.com',
            'password' => 'password',
        ];

        $response = $this->withHeaders([
            'Authorization' => env('USER_MONITORING'),
            'Accept' => 'application/json',
        ])->post('/api/login-monitoring/1868304912fabaa7f75db8f254b7a65d8ce0cfcbda70adcd3a2b7d3ed9f03eae', $requestData);

        $response->assertStatus(404);
        $response->assertJson(['message' => 'User not found!']);
    }

    public function test_login_user_monitoring_failed_because_token_is_invalid(): void
    {
        $hashedPassword = Hash::make(env('SALT_LOGGING') . 'password');

        UserMonitoring::create([
            'uuid' => Uuid::uuid4(),
            'name' => 'user',
            'email' => 'user@gmail.com',
            'password' => $hashedPassword,
        ]);

        $requestData = [
            'email' => 'user@gmail.com',
            'password' => '123456',
        ];

        $response = $this->withHeaders([
            'Authorization' => env('USER_MONITORING'),
            'Accept' => 'application/json',
        ])->post('/api/login-monitoring/1868304912fabaa7f75db8f254b7a65d8ce0cfcbda70adcd3a2b7d3ed9f03eae', $requestData);

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Invalid credentials!']);
    }
}
