<?php

namespace App\Http\Controllers;

use App\Persistance\Interface\PersistanceInterface;
use App\Repositories\StatusRepository;
use Illuminate\Http\Request;
class StatusController extends Controller
{
    public function __construct(StatusRepository $repository)
    {
        parent::__construct($repository);
    }

}
