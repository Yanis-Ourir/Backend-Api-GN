<?php

namespace App\Http\Controllers;

use App\Persistance\Interface\PersistanceInterface;
use App\Repository\PlatformRepository;
use Illuminate\Http\Request;

class PlatformController extends Controller
{
    public function __construct(PlatformRepository $repository, PersistanceInterface $persistance)
    {
        parent::__construct($repository, $persistance);
    }
}
