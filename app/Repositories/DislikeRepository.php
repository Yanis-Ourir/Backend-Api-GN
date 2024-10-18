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
        $dislike = $this->checkIfUserAlreadyDisliked($data);

        if(!isset($dislike['error'])) {
            $this->delete($dislike['id']);
            return ["error" => "User already disliked"];
        }

        try {
            $dislike = $this->model->create([
                'user_id' => $data['user_id'],
                'dislikeable_id' => $data['dislikeable_id'],
                'dislikeable_type' => $data['dislikeable_type'],
            ]);
        } catch (\Exception $e) {
            return ["error" => "Failed to create dislike"];
        }

        return $dislike->toArray();
    }

    public function checkIfUserAlreadyDisliked(array $data): array
    {
        $dislike = $this->model->where('user_id', $data['user_id'])
            ->where('dislikeable_id', $data['dislikeable_id'])
            ->where('dislikeable_type', $data['dislikeable_type'])
            ->first();

        if ($dislike) {
            return $dislike->toArray();
        }

        return ["error" => "Dislike not found"];
    }

    public function update(int|string $id, array $data): array
    {
        $dislike = $this->model->find($id);

        if (!$dislike) {
            return ["error" => "Dislike not found"];
        }

        $dislike->update(
            [
                'user_id' => $data['user_id'],
                'dislikeable_id' => $data['dislikeable_id'],
                'dislikeable_type' => $data['dislikeable_type'],
            ]
        );

        return $dislike->toArray();
    }
}
