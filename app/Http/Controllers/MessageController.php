<?php

namespace App\Http\Controllers;

use App\Persistance\Interface\PersistanceInterface;
use App\Repository\MessageRepository;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct(MessageRepository $repository, PersistanceInterface $persistance)
    {
        parent::__construct($repository, $persistance);
    }
}
