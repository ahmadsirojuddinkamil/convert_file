<?php

namespace Modules\Monitoring\tests\Feature\Middleware;

use Modules\Monitoring\App\Http\Middleware\UserMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class UserMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_authentication_user_monitoring_success(): void
    {
        $request = Request::create('/path/to/endpoint', 'GET');
        $request->headers->set('Authorization', env('USER_MONITORING'));

        $middleware = new UserMiddleware();

        $next = function ($request) {
            return $request;
        };

        $response = $middleware->handle($request, $next);
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $this->assertNull($responseData);
    }

    public function test_authentication_user_monitoring_failed_because_uuid_not_found(): void
    {
        $request = Request::create('/path/to/endpoint', 'GET');
        $request->headers->set('Authorization', '');

        $middleware = new UserMiddleware();

        $next = function ($request) {
            return $request;
        };

        $response = $middleware->handle($request, $next);
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $this->assertEquals(401, $response->status());
        $this->assertEquals('Token not found!', $responseData['message']);
    }

    public function test_authentication_user_monitoring_failed_because_not_token(): void
    {
        $request = Request::create('/path/to/endpoint', 'GET');
        $request->headers->set('Authorization', 'token');

        $middleware = new UserMiddleware();

        $next = function ($request) {
            return $request;
        };

        $response = $middleware->handle($request, $next);
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $this->assertEquals(401, $response->status());
        $this->assertEquals('Token not suitable!', $responseData['message']);
    }

    public function test_authentication_user_monitoring_failed_because_invalid_uuid(): void
    {
        $request = Request::create('/path/to/endpoint', 'GET');
        $request->headers->set('Authorization', '36b8009d76cd6b70822d11b6120a88243d71834df1db99b5a47d8395938fbd56');

        $middleware = new UserMiddleware();

        $next = function ($request) {
            return $request;
        };

        $response = $middleware->handle($request, $next);
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $this->assertEquals(401, $response->status());
        $this->assertEquals('Invalid Token!', $responseData['message']);
    }
}
