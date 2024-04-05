<?php

namespace App\Http\Controllers;

use App\Persistance\Interface\PersistanceInterface;
use App\Repositories\LikeRepository;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function __construct(LikeRepository $repository)
    {
        parent::__construct($repository);
    }
}
