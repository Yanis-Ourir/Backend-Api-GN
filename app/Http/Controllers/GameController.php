<?php

namespace App\Http\Controllers;

use App\Persistance\Interface\PersistanceInterface;
use App\Persistance\PersistanceMySQL;
use App\Repositories\GameRepository;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function __construct(GameRepository $repository)
    {
        parent::__construct($repository);
    }

    public function findFirstTenMostRatedGames(): array
    {
        /**
         * @var GameRepository $repository
         */
        return $this->repository->findFirstTenMostRatedGames();
    }



    public function findByColumn($column, $name): array
    {
        return $this->repository->findByColumn($column, $name);
    }
}
