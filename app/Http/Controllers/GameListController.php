<?php

namespace App\Http\Controllers;

use App\Persistance\Interface\PersistanceInterface;
use App\Repositories\GameListRepository;
use Illuminate\Http\Request;

class GameListController extends Controller
{
    public function __construct(GameListRepository $repository)
    {
        parent::__construct($repository);
    }
}
