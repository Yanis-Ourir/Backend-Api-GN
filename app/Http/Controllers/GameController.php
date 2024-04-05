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

    public function findByGameName($name): array
    {
        return $this->repository->findByName($name);
    }
}
