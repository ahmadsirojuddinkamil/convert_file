<?php

namespace Modules\Monitoring\App\Services;

use Modules\Monitoring\App\Http\Controllers\UserMonitoringController;
use Modules\Monitoring\App\Models\UserMonitoring;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class JwtMonitorService
{
    public function generateTokenJwt()
    {
        $hashedPassword = Hash::make(env('SALT_LOGGING') . 'password');

        UserMonitoring::create([
            'uuid' => Uuid::uuid4(),
            'name' => 'user',
            'email' => 'user@gmail.com',
            'password' => $hashedPassword,
        ]);

        $request = new Request([
            'email' => 'user@gmail.com',
            'password' => 'password',
        ]);

        $loginService = app(UserMonitoringController::class);
        $loginResponse = $loginService->login($request);

        return $loginResponse->original['token'];
    }
}
