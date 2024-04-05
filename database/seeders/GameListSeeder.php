<?php

namespace Database\Seeders;

use App\Models\GameList;
use App\Models\User;
use Illuminate\Database\Seeder;

class GameListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $user = new User();
        $userRandom = $user->inRandomOrder()->first();
        GameList::create([
                'name' => $faker->name,
                'description' => $faker->text,
                'is_private' => $faker->boolean,
                'user_id' => $userRandom->id,
            ]);
    }
}
