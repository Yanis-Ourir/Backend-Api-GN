<?php
namespace Tests\Unit;
use App\Models\Game;
use App\Models\Platform;
use App\Models\Tag;
use App\Repositories\GameRepository;
use App\Repositories\PlatformRepository;
use App\Repositories\TagRepository;
use Mockery;



// Add test for missing info about the game

class GameTest extends \Tests\TestCase
{
    public function testGameMissingDescription() {
        $gameRepository = new GameRepository(new Game(), new PlatformRepository(new Platform()), new TagRepository(new Tag()));

        $game = $gameRepository->create([
            'name' => 'game',
            'rating' => 10,
            'editor' => 'editor',
            'tags' => ['tag1', 'tag2'],
            'platforms' => ['platform1', 'platform2'],
            'release_date' => '2022-01-01'
        ]);

        $errors = $game['error']->getMessages();
        expect(array_key_exists('description', $errors))->toBe(true);

    }

    public function testGameMissingEditor() {
        $gameRepository = new GameRepository(new Game(), new PlatformRepository(new Platform()), new TagRepository(new Tag()));

        $game = $gameRepository->create([
            'name' => 'game',
            'rating' => 10,
            'description' => 'description',
            'tags' => ['tag1', 'tag2'],
            'platforms' => ['platform1', 'platform2'],
            'release_date' => '2022-01-01'
        ]);

        $errors = $game['error']->getMessages();
        expect(array_key_exists('editor', $errors))->toBe(true);

    }
}

it('create games', function() {
    $gameRepository = Mockery::mock(GameRepository::class);
    $gameRepository->shouldReceive('create')->andReturn([
        'name' => 'game',
        'description' => 'description',
        'price' => 10
    ]);

    $checkGame = $gameRepository->create([
        'name' => 'game',
        'description' => 'description',
        'price' => 10
    ]);

    expect($checkGame['name'])->toBe('game')
        ->and($checkGame['description'])->toBe('description')
        ->and($checkGame['price'])->toBe(10);
});


// Add test for updating the game

// Add test for deleting the game
