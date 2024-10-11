<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
    }

    public function findByPseudo($pseudo): array
    {
        return $this->repository->findByName($pseudo);
    }

    public function findUsersWhoRatedSameGames(string $userId, int $gameId): array
    {
        return $this->repository->findUsersWhoRatedSameGames($userId, $gameId);
    }

}
