<?php

namespace Modules\Monitoring\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Monitoring\App\Models\UserMonitoring;
use Ramsey\Uuid\Uuid;

class MonitoringDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserMonitoring::create([
            'uuid' => Uuid::uuid4(),
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => '123456'
        ]);
    }
}
