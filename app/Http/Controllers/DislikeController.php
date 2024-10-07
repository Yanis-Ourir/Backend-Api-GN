<?php

namespace App\Http\Controllers;

use App\Persistance\Interface\PersistanceInterface;
use App\Repositories\DislikeRepository;
use Illuminate\Http\Request;

class DislikeController extends Controller
{
    public function __construct(DislikeRepository $repository)
    {
        parent::__construct($repository);
    }

    public function checkIfUserAlreadyDisliked($dislikeableId, string $userId, string $dislikeableType): array
    {
        $data = [
            'dislikeable_id' => $dislikeableId,
            'user_id' => $userId,
            'dislikeable_type' => 'App\\Models\\' . $dislikeableType
        ];

        return $this->repository->checkIfUserAlreadyDisliked($data);
    }
}
