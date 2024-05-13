<?php

namespace Modules\Monitoring\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json(['message' => 'Token not found!'], 401);
        }

        if (strpos($token, 'Bearer ') === 0) {
            $token = substr($token, 7);
        }

        if (!preg_match('/^[a-f0-9]{64}$/i', $token)) {
            return response()->json(['message' => 'Token not suitable!'], 401);
        }

        $secretKey = env('USER_MONITORING');

        if ($token !== $secretKey) {
            return response()->json(['message' => 'Invalid Token!'], 401);
        }

        return $next($request);
    }
}
