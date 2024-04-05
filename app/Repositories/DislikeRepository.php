<?php

namespace App\Repositories;

use App\Models\Dislike;
use App\Models\User;
use OpenApi\Annotations as OA;

class DislikeRepository extends Repository
{
    public function __construct(Dislike $model)
    {
        parent::__construct($model);
    }

    /**
     * @OA\Post(
     *     path="/dislikes",
     *     tags={"dislikes"},
     *     summary="Create a new dislike",
     *     description="Create a new dislike",
     *     @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *     required={"user_id", "dislikeable_id", "dislikeable_type"},
     *     @OA\Property(property="user_id", type="string", example="e2a1b1c1-1a1b-1c1d-1e1f-1a1b1c1d1e1f"),
     *     @OA\Property(property="dislikeable_id",oneOf={
     *          @OA\Schema(type="string", example="e2a1b1c1-1a1b-1c1d-1e1f-1a1b1c1d1e1f"),
     *          @OA\Schema(type="integer", example=1)
     *      }
     *     ),
     *     @OA\Property(property="dislikeable_type", type="string", example="App\Models\Game")
     *  )
     * ),
     *     @OA\Response(
     *     response=201,
     *     description="Dislike created successfully"
     * ),
     *     @OA\Response(
     *     response=400,
     *     description="Failed to create dislike",
     *     @OA\JsonContent(
     *     @OA\Property(property="error", type="string", example="Invalid data provided")
     *     )
     *    )
     * )
     * )
     */

    public function create(array $data): array
    {
        $dislike = $this->model::create([
            'user_id' => User::find($data['user_id']),
            'dislikeable_id' => $data['dislikeable_id'],
            'dislikeable_type' => $data['dislikeable_type'],
        ]);

        $dislike->save();

        return $dislike->toArray();
    }

}
