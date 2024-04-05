<?php

namespace App\Http\Controllers;

use App\Persistance\Interface\PersistanceInterface;
use App\Repositories\ReviewRepository;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(ReviewRepository $repository)
    {
        parent::__construct($repository);
    }
}
