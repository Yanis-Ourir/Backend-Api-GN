<?php

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 25; $i++) {
            Game::create([
                'name' => $faker->name,
                'description' => $faker->text,
                'release_date' => $faker->date,
                'editor' => $faker->name,
                'rating' => $faker->randomDigit,
            ])->tags()->attach(rand(1, 10));
        }
    }
}
