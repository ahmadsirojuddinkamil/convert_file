<?php

namespace Modules\Monitoring\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Monitoring\App\Models\UserMonitoring;
use Ramsey\Uuid\Uuid;

class UserMonitoringFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = UserMonitoring::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'uuid' => Uuid::uuid4(),
            'name' => 'user',
            'email' => 'user@gmail.com',
            'password' => 'password'
        ];
    }
}
