<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Persistance\Interface\PersistanceInterface;
use App\Persistance\PersistanceMySQL;
use App\Repositories\Interface\RepositoryInterface;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
    }

    public function findByUserName($name): array
    {
        return $this->repository->findByName($name);
    }
}
