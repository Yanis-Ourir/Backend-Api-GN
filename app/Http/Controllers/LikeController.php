<?php

namespace App\Http\Controllers;

use App\Persistance\Interface\PersistanceInterface;
use App\Repositories\LikeRepository;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function __construct(LikeRepository $repository)
    {
        parent::__construct($repository);
    }

    public function checkIfUserAlreadyLiked($likeableId, string $userId, string $likeableType): array
    {
        $data = [
            'likeable_id' => $likeableId,
            'user_id' => $userId,
            'likeable_type' => 'App\\Models\\' . $likeableType
        ];

        return $this->repository->checkIfUserAlreadyLiked($data);
    }
}
