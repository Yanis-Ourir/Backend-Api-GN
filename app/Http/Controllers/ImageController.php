<?php

namespace App\Http\Controllers;

use App\Persistance\Interface\PersistanceInterface;
use App\Repository\ImageRepository;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function __construct(ImageRepository $repository, PersistanceInterface $persistance)
    {
        parent::__construct($repository, $persistance);
    }
}
