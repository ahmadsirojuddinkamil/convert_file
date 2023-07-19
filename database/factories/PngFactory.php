<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Png>
 */
class PngFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = app(Faker::class);

        $filePath = 'document_jpg_to_png/';
        $file = $faker->image('public/storage/' . $filePath, 640, 480, null, false);
        $fileName = pathinfo($file, PATHINFO_BASENAME);

        return [
            // 'jpg_id' => '1',
            'uuid' => $faker->uuid,
            'unique_id' => $faker->uuid,
            'file' => $filePath . $fileName,
            'name' => $faker->word,
        ];
    }
}
