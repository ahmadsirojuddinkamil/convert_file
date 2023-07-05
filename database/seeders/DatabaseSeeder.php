<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\File;
use App\Models\Pdf;
use App\Models\Word;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $faker = Faker::create('id_ID');

        for ($i = 1; $i <= 5; $i++) {

            $createdAt = $faker->dateTimeBetween('-5 years', 'now');
            $updatedAt = Carbon::instance($createdAt)->addDays(rand(1, 7));

            Pdf::create([
                'uuid' => $faker->uuid(),
                'name' => $faker->name(),
                'file' => $faker->text(),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ]);

            Word::create([
                'uuid' => $faker->uuid(),
                'name' => $faker->name(),
                'file' => $faker->text(),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ]);

        }
    }
}
