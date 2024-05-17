<?php

use App\Models\GameList;
use App\Models\Interfaces\UserInterface;
use App\Repositories\GameListRepository;

class UserFake implements UserInterface
{

}

it('user create a list', function() {
   $listRepository = Mockery::mock(GameListRepository::class);
    $listRepository->shouldReceive('create')->andReturn([
         'name' => 'list',
         'description' => 'description',
         'user' => new UserFake(),
    ]);

    $list = $listRepository->create([
        'name' => 'list',
        'description' => 'description',
        'user' => new UserFake(),
    ]);

    expect($list['name'])->toBe('list')
        ->and($list['description'])->toBe('description')
        ->and($list['user'])->toBeInstanceOf(UserInterface::class);
});

it('add a game to a list', function() {
   $listRepository = Mockery::mock(GameListRepository::class);
    $listRepository->shouldReceive('addGameToList')->andReturn([
         'name' => 'list',
         'description' => 'description',
         'games' => [
             [
                 'name' => 'game',
                 'description' => 'description',
             ]
         ]
    ]);

    $list = $listRepository->addGameToList([
        'name' => 'list',
        'description' => 'description',[
            'name' => 'game',
            'description' => 'description',
        ]
    ]);

    expect($list['name'])->toBe('list')
        ->and($list['description'])->toBe('description')
        ->and($list['user'])->toBeInstanceOf(UserInterface::class)
        ->and($list['games'][0]['name'])->toBe('game')
        ->and($list['games'][0]['description'])->toBe('description');
});

it('remove a game from a list', function() {
   $listRepository = Mockery::mock(GameListRepository::class);
    $listRepository->shouldReceive('removeGameFromList')->andReturn([
         'name' => 'list',
         'description' => 'description',
         'games' => []
    ]);

    $list = $listRepository->removeGameFromList([
        'name' => 'list',
        'description' => 'description',[
            'name' => 'game',
            'description' => 'description',
        ]
    ]);

    expect($list['name'])->toBe('list')
        ->and($list['description'])->toBe('description')
        ->and($list['user'])->toBeInstanceOf(UserInterface::class)
        ->and($list['games'])->toBe([]);
});

it('check if a list is private', function() {
   $list = Mockery::mock(GameList::class);
    $list->shouldReceive('isPrivate')->andReturn("Cette liste est privée");

    $isPrivate = $list->isPrivate();
    expect($isPrivate)->toBe("Cette liste est privée");

});
