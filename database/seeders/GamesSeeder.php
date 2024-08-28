<?php

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 25; $i++) {
            $name = $faker->name;
            $game = Game::create([
                'name' => $name,
                'description' => $faker->text,
                'release_date' => $faker->date,
                'editor' => $faker->name,
                'slug' => Str::of($name)->slug('-'),
                'rating' => $faker->randomDigit,
            ]);

            $game->platforms()->attach(rand(1, 10));
            $game->tags()->attach(rand(1, 10));

        }
    }
}
