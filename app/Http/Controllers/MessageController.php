<?php

namespace App\Http\Controllers;

use App\Persistance\Interface\PersistanceInterface;
use App\Repositories\MessageRepository;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct(MessageRepository $repository)
    {
        parent::__construct($repository);
    }
}
