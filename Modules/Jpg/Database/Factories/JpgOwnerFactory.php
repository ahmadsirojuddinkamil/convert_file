<?php

namespace Modules\Jpg\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Jpg\App\Models\Jpg;
use Ramsey\Uuid\Uuid;

class JpgOwnerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Jpg::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'png_uuid' => null,
            'pdf_uuid' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'owner' => Uuid::uuid4()->toString(),
            'file' => null,
            'name' => null,
        ];
    }
}
