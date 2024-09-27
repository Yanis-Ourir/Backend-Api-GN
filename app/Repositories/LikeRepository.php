<?php

namespace App\Repositories;

use App\Models\Like;
use App\Models\User;
use OpenApi\Annotations as OA;

class LikeRepository extends Repository
{
    public function __construct(Like $model)
    {
        parent::__construct($model);
    }

    /**
     * @OA\Post(
     *     path="/likes",
     *     tags={"likes"},
     *     summary="Create a new like",
     *     description="Create a new like for a list or an evaluation",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "likeable_id", "likeable_type"},
     *             @OA\Property(
     *                 property="user_id",
     *                 type="string",
     *                 example="e2a1b1c1-1a1b-1c1d-1e1f-1a1b1c1d1e1f"
     *             ),
     *             @OA\Property(
     *                 property="likeable_id",
     *                 oneOf={
     *                     @OA\Schema(type="string", example="e2a1b1c1-1a1b-1c1d-1e1f-1a1b1c1d1e1f"),
     *                     @OA\Schema(type="integer", example=1)
     *                 }
     *             ),
     *             @OA\Property(
     *                 property="likeable_type",
     *                 type="string",
     *                 example="App\Models\List"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Like created successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Failed to create like",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Invalid data provided"
     *             )
     *         )
     *     )
     * )
     */

    public function create(array $data): array
    {
        $like = $this->model::create([
            'user_id' => User::find($data['user_id']),
            'likeable_id' => $data['likeable_id'],
            'likeable_type' => $data['likeable_type'],
        ]);

        return $like->toArray();
    }

    // add a method to find a like by user id

    /**
     * @OA\Get(
     *     path="/likes/{userId}",
     *     tags={"likes"},
     *     summary="Find likes by user id",
     *     description="Returns a list of likes by user id",
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         description="ID of the user",
     *         @OA\Schema(
     *             type="string",
     *             example="e2a1b1c1-1a1b-1c1d-1e1f-1a1b1c1d1e1f"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A list of likes",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No likes found",
     *     )
     * )
     */
    public function findByUserId(string $userId): array
    {
        $like = $this->model::where('user_id', $userId)->get();

        return $like->toArray();
    }

}
