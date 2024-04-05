<?php

namespace App\Http\Controllers;

use App\Persistance\Interface\PersistanceInterface;
use App\Repositories\Interface\RepositoryInterface;
use App\Repositories\TagRepository;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct(TagRepository $repository)
    {
        parent::__construct($repository);
    }

}
