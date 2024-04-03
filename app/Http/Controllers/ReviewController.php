<?php

namespace App\Http\Controllers;

use App\Persistance\Interface\PersistanceInterface;
use App\Repository\ReviewRepository;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(ReviewRepository $repository, PersistanceInterface $persistance)
    {
        parent::__construct($repository, $persistance);
    }
}
