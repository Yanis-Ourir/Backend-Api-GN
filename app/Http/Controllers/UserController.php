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

    public function findByPseudo(string $pseudo): array
    {
        return $this->repository->findByName($pseudo);
    }

    public function findByUserId(string $id): array
    {
        return $this->repository->findByUserId($id);
    }

}
