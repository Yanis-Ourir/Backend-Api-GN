<?php

namespace App\Http\Controllers;

use App\Persistance\Interface\PersistanceInterface;
use App\Repositories\GameListRepository;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class GameListController extends Controller
{
    public function __construct(GameListRepository $repository)
    {
        parent::__construct($repository);
    }

    public function createList(Request $request): array
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $data['image'] = $image;
        }
        return $this->repository->create($data);
    }

    public function findGameListByUserId(string $userId): array
    {
        return $this->repository->findGameListByUserId($userId);
    }

    public function checkIfGameIsAlreadyInTheList(string $userId, string $gameId): array
    {
        return $this->repository->checkIfGameIsAlreadyInTheList($userId, $gameId);
    }

    public function findMostLikedList(int $limit): array
    {
        return $this->repository->findMostLikedList($limit);
    }

    public function addGame(Request $request): array
    {
        $data = $request->json()->all();
        return $this->repository->addGameToList($data);
    }

    public function removeGameFromList(Request $request): array
    {
        $data = $request->json()->all();
        return $this->repository->removeGameFromList($data);
    }
}
