<?php

namespace App\Http\Controllers;

use App\Persistance\Interface\PersistanceInterface;
use App\Repository\Interface\RepositoryInterface;
use App\Repository\TagRepository;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct(TagRepository $repository, PersistanceInterface $persistance)
    {
        parent::__construct($repository, $persistance);
    }

}
