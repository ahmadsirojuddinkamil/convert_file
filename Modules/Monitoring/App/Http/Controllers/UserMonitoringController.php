<?php

namespace Modules\Monitoring\App\Http\Controllers;

use Modules\Monitoring\App\Models\UserMonitoring;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Modules\Monitoring\App\Services\ValidationMonitorService;
use Ramsey\Uuid\Uuid;

class UserMonitoringController extends Controller
{
    protected $validationMonitorService;

    public function __construct(ValidationMonitorService $validationMonitorService)
    {
        $this->validationMonitorService = $validationMonitorService;
    }

    public function register(Request $request)
    {
        $validator = $this->validationMonitorService->validationRegister($request);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $userExists = UserMonitoring::where('email', $request->email)->where('name', $request->name)->first();

        if ($userExists) {
            return response()->json(['message' => 'user already in use!'], 409);
        }

        $salt = env('SALT_LOGGING');
        $hashedPassword = Hash::make($salt . $request->password);

        $user = UserMonitoring::create([
            'uuid' => Uuid::uuid4(),
            'name' => $request->name,
            'email' => $request->email,
            'password' => $hashedPassword,
        ]);

        return response()->json(['message' => 'success register user monitoring!', 'user' => $user], 200);
    }

    public function login(Request $request)
    {
        $validator = $this->validationMonitorService->validationLogin($request);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = UserMonitoring::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found!'], 404);
        }

        if (Hash::check(env('SALT_LOGGING') . $request->password, $user->password)) {
            $payload = [
                'sub' => $user->email,
                'name' => $user->name,
                'iat' => time(),
                'exp' => time() + (60 * 60),
                'jti' => uniqid(),
            ];

            $secretKey = env('JWT_SECRET_MONITORING');
            $token = JWT::encode($payload, $secretKey, 'HS256');

            $startTime = date('Y-m-d H:i:s', $payload['iat']);
            $expiryTime = date('Y-m-d H:i:s', $payload['exp']);

            $tokenTime = [
                'start_time' => $startTime,
                'expiry_time' => $expiryTime,
            ];

            return response()->json(['message' => 'Login successful!', 'token' => $token, 'token_time' => $tokenTime], 200);
        }

        return response()->json(['message' => 'Invalid credentials!'], 401);
    }
}
