<?php

namespace App\Http\Controllers;

use App\Persistance\Interface\PersistanceInterface;
use App\Repository\LikeRepository;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function __construct(LikeRepository $repository, PersistanceInterface $persistance)
    {
        parent::__construct($repository, $persistance);
    }
}
