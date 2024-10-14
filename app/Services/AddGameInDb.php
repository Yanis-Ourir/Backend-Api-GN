<?php

namespace App\Services;

use App\Models\Game;
use App\Repositories\GameRepository;
use Illuminate\Support\Str;

class AddGameInDb
{


    public function findGameInApi(string $slug): array
    {
        // https://api.rawg.io/api/games/pokemon-ruby?key=CLE_API
        $url = "https://api.rawg.io/api/games/$slug?key=" . env('RAWG_API_KEY');
        try {
            $response = file_get_contents($url);
        } catch (\Exception $e) {
            return ['error' => 'Game not found'];
        }
        $data = json_decode($response, true);
        return $this->sortNeededData($data);
    }

    public function sortNeededData($data): array
    {
        $platformsNames = [];
        if (!empty($data['platforms'])) {
            foreach ($data['platforms'] as $platform) {
                $platformsNames[] = $platform['platform']['name'];
            }
        }

        $tagsNames = [];
        if (!empty($data['genres'])) {
            foreach ($data['genres'] as $tag) {
                $tagsNames[] = $tag['name'];
            }
        }


        return [
            'name' => $data['name'],
            'description' => Str::limit($data['description_raw'], 700),
            'editor' => $data['publishers'][0]['name'],
            'rating' => 0,
            'slug' => $data['slug'],
            'release_date' => $data['released'],
            'platforms' => $platformsNames,
            'tags' => $tagsNames,
            'image' => $data['background_image']
        ];
    }

}
