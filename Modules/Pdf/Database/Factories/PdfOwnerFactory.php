<?php

namespace Modules\Pdf\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Pdf\App\Models\Pdf;
use Ramsey\Uuid\Uuid;

class PdfOwnerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Pdf::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'jpg_uuid' => null,
            'png_uuid' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'owner' => Uuid::uuid4()->toString(),
            'file' => null,
            'name' => null,
            'preview' => null,
        ];
    }
}
