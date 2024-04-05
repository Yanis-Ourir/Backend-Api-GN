<?php

namespace App\Http\Controllers;

use App\Persistance\Interface\PersistanceInterface;
use App\Repositories\PlatformRepository;
use Illuminate\Http\Request;

class PlatformController extends Controller
{
    public function __construct(PlatformRepository $repository)
    {
        parent::__construct($repository);
    }
}
