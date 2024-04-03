<?php

namespace App\Http\Controllers;

use App\Persistance\Interface\PersistanceInterface;
use App\Repository\GameRepository;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function __construct(GameRepository $repository, PersistanceInterface $persistance)
    {
        parent::__construct($repository, $persistance);
    }
}
