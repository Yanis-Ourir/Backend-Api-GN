<?php

namespace Tests\Unit;

use App\Services\ExternalsApi\Api\Rawgio;
use Tests\TestCase;
use Mockery;

class ExternalApiTest extends TestCase
{
    public function testExternalApi(): void
    {
        $rawgio = Mockery::mock(Rawgio::class);
        $rawgio->shouldReceive('findGameInApi')->andReturn([
            "name" => "Super Mario Galaxy",
            "description" => "Become Mario as he traverses gravity-bending galaxies, traveling in and out of gravitational fields by blasting from planet to planet. Control Mario with the Wii Remote™ and Nunchuk™. Power-up to reach inaccessible areas. Collect Star Bits to either stun enemies or feed Lumas.",
            "editor" => "Nintendo",
            "rating" => 0,
            "slug" => "super-mario-galaxy",
            "release_date" => "2007-11-01",
            "platforms" => [
                "Wii"
            ],
            "tags" => [
                "Platformer"
            ],
            "image" => "https://media.rawg.io/media/games/936/936f0ffac0b3c9f5c8d185f610ed2631.jpg"
        ]);

        $game = $rawgio->findGameInApi('super-mario-galaxy');

        expect($game['name'])->toBe('Super Mario Galaxy')
            ->and($game['description'])->toBe('Become Mario as he traverses gravity-bending galaxies, traveling in and out of gravitational fields by blasting from planet to planet. Control Mario with the Wii Remote™ and Nunchuk™. Power-up to reach inaccessible areas. Collect Star Bits to either stun enemies or feed Lumas.')
            ->and($game['editor'])->toBe('Nintendo')
            ->and($game['rating'])->toBe(0)
            ->and($game['slug'])->toBe('super-mario-galaxy')
            ->and($game['release_date'])->toBe('2007-11-01')
            ->and($game['platforms'])->toBe(['Wii'])
            ->and($game['tags'])->toBe(['Platformer'])
            ->and($game['image'])->toBe('https://media.rawg.io/media/games/936/936f0ffac0b3c9f5c8d185f610ed2631.jpg');
    }

    public function testExternalApiError(): void
    {
        $rawgio = Mockery::mock(Rawgio::class);
        $rawgio->shouldReceive('findGameInApi')->andReturn(['error' => 'Game not found']);

        $game = $rawgio->findGameInApi('sdsdkvdskvkxckvkdskvslvl');

        expect($game)->toBe(['error' => 'Game not found']);
    }
}
