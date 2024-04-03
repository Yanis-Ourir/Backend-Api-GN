<?php

namespace App\Http\Controllers;

use App\Persistance\Interface\PersistanceInterface;
use App\Repository\StatusRepository;
use Illuminate\Http\Request;
class StatusController extends Controller
{
    public function __construct(StatusRepository $repository, PersistanceInterface $persistance)
    {
        parent::__construct($repository, $persistance);
    }

}
