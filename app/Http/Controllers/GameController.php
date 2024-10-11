<?php

namespace App\Http\Controllers;

use App\Repositories\GameRepository;

class GameController extends Controller
{
    public function __construct(GameRepository $repository)
    {
        parent::__construct($repository);
    }

    public function findFirstTenMostRatedGames(): array
    {
        return $this->repository->findFirstTenMostRatedGames();
    }

    public function findByColumn($column, $name): array
    {
        return $this->repository->findByColumn($column, $name);
    }

    public function findByUserSearch($search): array
    {
        return $this->repository->findByUserSearch($search);
    }

    public function findGamesThatUserCanLike(string $userId): array
    {
        return $this->repository->findGamesThatUserCanLike($userId);
    }
}
