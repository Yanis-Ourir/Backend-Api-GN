<?php

namespace App\Http\Controllers;

use App\Persistance\Interface\PersistanceInterface;
use App\Repositories\DislikeRepository;
use Illuminate\Http\Request;

class DislikeController extends Controller
{
    public function __construct(DislikeRepository $repository)
    {
        parent::__construct($repository);
    }
}
