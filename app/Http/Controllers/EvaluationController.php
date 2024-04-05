<?php

namespace App\Http\Controllers;

use App\Persistance\Interface\PersistanceInterface;
use App\Repositories\GameRepository;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function __construct(GameRepository $repository)
    {
        parent::__construct($repository);
    }
}
