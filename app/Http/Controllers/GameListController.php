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

    public function addGame(Request $request): array
    {
        $data = $request->json()->all();
        return $this->repository->addGameToList($data);
    }
}
