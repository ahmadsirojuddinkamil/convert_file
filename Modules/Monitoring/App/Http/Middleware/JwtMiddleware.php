<?php

namespace Modules\Monitoring\App\Http\Middleware;

use Firebase\JWT\{BeforeValidException, ExpiredException, JWT, Key, SignatureInvalidException};
use Illuminate\Http\Request;
use Closure;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $jwtToken = $request->header('Authorization');

        if (!$jwtToken) {
            return response()->json(['message' => 'Token not found!'], 401);
        }

        $token = $jwtToken;

        if (strpos($jwtToken, 'Bearer ') === 0) {
            $token = substr($jwtToken, 7);
        }

        if (!preg_match('/^[a-zA-Z0-9-_]+\.[a-zA-Z0-9-_]+\.[a-zA-Z0-9-_]+$/', $token)) {
            return response()->json(['message' => 'Invalid JWT format!'], 401);
        }

        try {
            JWT::decode($token, new Key(env('JWT_SECRET_MONITORING'), 'HS256'));
            return $next($request);
        } catch (ExpiredException $error) {
            return response()->json(['error' => 'Token is expired'], 401);
        } catch (BeforeValidException $error) {
            return response()->json(['error' => 'Token is not yet valid'], 401);
        } catch (SignatureInvalidException $error) {
            return response()->json(['error' => 'Invalid token'], 401);
        } catch (\Exception $error) {
            return response()->json(['error' => 'Failed to decode token'], 500);
        }
    }
}
