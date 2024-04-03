<?php

namespace App\Http\Controllers;

use App\Persistance\Interface\PersistanceInterface;
use App\Repository\GameListRepository;
use Illuminate\Http\Request;

class GameListController extends Controller
{
    public function __construct(GameListRepository $repository, PersistanceInterface $persistance)
    {
        parent::__construct($repository, $persistance);
    }
}
