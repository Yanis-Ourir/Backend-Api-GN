<?php

namespace App\Http\Controllers;

use App\Persistance\Interface\PersistanceInterface;
use App\Repositories\ImageRepository;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function __construct(ImageRepository $repository)
    {
        parent::__construct($repository);
    }
}
