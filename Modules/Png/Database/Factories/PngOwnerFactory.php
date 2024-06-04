<?php

namespace Modules\Png\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Png\App\Models\Png;
use Ramsey\Uuid\Uuid;

class PngOwnerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Png::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'jpg_uuid' => null,
            'pdf_uuid' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'owner' => Uuid::uuid4()->toString(),
            'file' => null,
            'name' => null,
        ];
    }
}
