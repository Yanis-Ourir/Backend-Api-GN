<?php

namespace App\Http\Controllers;

use App\Persistance\Interface\PersistanceInterface;
use App\Repository\DislikeRepository;
use Illuminate\Http\Request;

class DislikeController extends Controller
{
    public function __construct(DislikeRepository $repository, PersistanceInterface $persistance)
    {
        parent::__construct($repository, $persistance);
    }
}
