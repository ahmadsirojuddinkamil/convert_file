<?php

namespace Modules\Monitoring\tests\Feature\Middleware;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Monitoring\App\Http\Middleware\JwtMiddleware;
use Illuminate\Http\Request;
use Modules\Monitoring\App\Services\JwtMonitorService;
use Tests\TestCase;

class JwtMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected $jwtService;

    public function setUp(): void
    {
        parent::setUp();
        $this->jwtService = new JwtMonitorService();
    }

    public function test_authentication_jwt_monitoring_success(): void
    {
        $tokenJwt = $this->jwtService->generateTokenJwt();

        $request = Request::create('/path/to/endpoint', 'GET');
        $request->headers->set('Authorization', $tokenJwt);

        $middleware = new JwtMiddleware();

        $next = function ($request) {
            return $request;
        };

        $response = $middleware->handle($request, $next);
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $this->assertNull($responseData);
    }

    public function test_auth_user_monitoring_failed_because_token_not_found(): void
    {
        $request = Request::create('/path/to/endpoint', 'GET');
        $request->headers->set('Authorization', '');

        $middleware = new JwtMiddleware();

        $next = function ($request) {
            return $request;
        };

        $response = $middleware->handle($request, $next);
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $this->assertEquals(401, $response->status());
        $this->assertEquals('Token not found!', $responseData['message']);
    }

    public function test_auth_user_monitoring_failed_because_not_token(): void
    {
        $request = Request::create('/path/to/endpoint', 'GET');
        $request->headers->set('Authorization', 'token');

        $middleware = new JwtMiddleware();

        $next = function ($request) {
            return $request;
        };

        $response = $middleware->handle($request, $next);
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $this->assertEquals(401, $response->status());
        $this->assertEquals('Invalid JWT format!', $responseData['message']);
    }

    public function test_auth_user_monitoring_failed_because_invalid_token(): void
    {
        $request = Request::create('/path/to/endpoint', 'GET');
        $request->headers->set('Authorization', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c');

        $middleware = new JwtMiddleware();

        $next = function ($request) {
            return $request;
        };

        $response = $middleware->handle($request, $next);
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $this->assertEquals(401, $response->status());
        $this->assertEquals('Invalid token', $responseData['error']);
    }
}
