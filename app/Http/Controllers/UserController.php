<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Persistance\Interface\PersistanceInterface;
use App\Repository\Interface\RepositoryInterface;
use App\Repository\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(UserRepository $repository, PersistanceInterface $persistance)
    {
        parent::__construct($repository, $persistance);
    }
}
