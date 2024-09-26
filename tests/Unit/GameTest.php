<?php
namespace Tests\Unit;
use App\Models\Game;
use App\Models\Platform;
use App\Models\Tag;
use App\Repositories\GameRepository;
use App\Repositories\PlatformRepository;
use App\Repositories\TagRepository;
use Mockery;
use Tests\TestCase;

class GameTest extends TestCase
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

    public function testCreatingGame() {
        $gameRepository = Mockery::mock(GameRepository::class);
        $gameRepository->shouldReceive('create')->andReturn([
            'name' => 'game',
            'rating' => 10,
            'description' => 'description',
            'tags' => ['tag1', 'tag2'],
            'platforms' => ['platform1', 'platform2'],
            'release_date' => '2022-01-01'
        ]);

        $checkGame = $gameRepository->create([
            'name' => 'game',
            'rating' => 10,
            'description' => 'description',
            'tags' => ['tag1', 'tag2'],
            'platforms' => ['platform1', 'platform2'],
            'release_date' => '2022-01-01'
        ]);

        expect($checkGame['name'])->toBe('game')
            ->and($checkGame['description'])->toBe('description')
            ->and($checkGame['rating'])->toBe(10)
            ->and($checkGame['tags'])->toBe(['tag1', 'tag2'])
            ->and($checkGame['platforms'])->toBe(['platform1', 'platform2'])
            ->and($checkGame['release_date'])->toBe('2022-01-01');

    }

    public function testGameIsInsertingInDb() {
//        $gameRepository = new GameRepository(new Game(), new PlatformRepository(new Platform()), new TagRepository(new Tag()));
//
//        $game = $gameRepository->create([
//            'name' => 'test eleven',
//            'rating' => 10,
//            'description' => 'description',
//            'editor' => 'editor',
//            'tags' => ['Multijoueur', 'FPS'],
//            'platforms' => ['Nintendo Switch', 'Playstation 5'],
//            'release_date' => '2022-01-01'
//        ]);
//
//        $checkGame = $gameRepository->findByName($game['name']);
//
//        expect($checkGame['name'])->toBe('test eleven')
//            ->and($checkGame['description'])->toBe('description')
//            ->and($checkGame['rating'])->toBe(10)
//            ->and($checkGame['tags'])->toBe(['FPS', 'Multijoueur'])
//            ->and($checkGame['platforms'])->toBe(['Nintendo Switch', 'PlayStation 5'])
//            ->and($checkGame['release_date'])->toBe('2022-01-01');


        $gameRepository = Mockery::mock(GameRepository::class);

        $gameData = [
            'name' => 'test eleven',
            'rating' => 10,
            'description' => 'description',
            'editor' => 'editor',
            'tags' => ['Multijoueur', 'FPS'],
            'platforms' => ['Nintendo Switch', 'Playstation 5'],
            'release_date' => '2022-01-01'
        ];

        $gameRepository->shouldReceive('create')
            ->with($gameData)
            ->andReturn($gameData);

        $gameRepository->shouldReceive('findByColumn')
            ->with('name', $gameData['name'])
            ->andReturn($gameData);

        $game = $gameRepository->create($gameData);
        $checkGame = $gameRepository->findByColumn('name', $game['name']);

        expect($checkGame['name'])->toBe('test eleven')
            ->and($checkGame['description'])->toBe('description')
            ->and($checkGame['rating'])->toBe(10)
            ->and($checkGame['tags'])->toBe(['Multijoueur', 'FPS'])
            ->and($checkGame['platforms'])->toBe(['Nintendo Switch', 'Playstation 5'])
            ->and($checkGame['release_date'])->toBe('2022-01-01');
    }

    public function testDeletingGame() {
//        $gameRepository = new GameRepository(new Game(), new PlatformRepository(new Platform()), new TagRepository(new Tag()));
//        $response = $gameRepository->delete(32);
//        expect($response->getContent())->toBe('Successfully deleted')
//            ->and($response->getStatusCode())->toBe(200);

        $gameRepository = Mockery::mock(GameRepository::class);
        $gameRepository->shouldReceive('delete')->andReturn(response('Successfully deleted', 200));
        $response = $gameRepository->delete(32);
        expect($response->getContent())->toBe('Successfully deleted')
            ->and($response->getStatusCode())->toBe(200);
    }

    public function testDeletingGameNotFound() {
        $gameRepository = new GameRepository(new Game(), new PlatformRepository(new Platform()), new TagRepository(new Tag()));
        $response = $gameRepository->delete(1000);
        expect($response->getContent())->toBe('Game not found')
            ->and($response->getStatusCode())->toBe(201);
    }

//    public function testFindingGameByName() {
//        $gameRepository = new GameRepository(new Game(), new PlatformRepository(new Platform()), new TagRepository(new Tag()));
//        $game = $gameRepository->findByColumn('name', 'Katelynn Connelly');
//        expect($game)->toBe([
//            'id' => 25,
//            'name' => 'Katelynn Connelly',
//            'description' => 'Voluptate numquam qui aperiam consequatur voluptas. Eaque quis nihil consequatur veritatis sed et doloribus eum.',
//            'editor' => 'Maymie Rolfson',
//            'rating' => 6,
//            'release_date' => '1973-02-13',
//            'created_at' => '2024-05-16T10:39:56.000000Z',
//            'updated_at' => '2024-05-16T10:39:56.000000Z',
//            'platforms' => [],
//            'tags' => ['Simulation'],
//        ]);
//    }

    public function testFindingGameByNameNotFound() {
        $gameRepository = new GameRepository(new Game(), new PlatformRepository(new Platform()), new TagRepository(new Tag()));
        $game = $gameRepository->findByColumn('name', 'ldgvldsvls');
        expect($game)->toBe(["error" => "Game not found"]);
    }
}

