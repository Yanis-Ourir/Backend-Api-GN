<?php

namespace App\Repositories;

use App\Models\Game;
use App\Models\GameList;
use App\Models\Platform;
use App\Models\Review;
use App\Models\Status;
use OpenApi\Annotations as OA;

class ReviewRepository extends Repository
{
    public function __construct(Review $model)
    {
        parent::__construct($model);
    }

    /**
     * @OA\Post(
     *     path="/reviews",
     *     tags={"reviews"},
     *     summary="Create a new review",
     *     description="Create a new review for a game to add to a game list",
     *     @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *     required={"rating", "description", "game_time", "game_id", "game_list_id", "status_id"},
     *     @OA\Property(property="rating", type="integer", example=10),
     *     @OA\Property(property="description", type="string", example="This game is amazing!"),
     *     @OA\Property(property="game_time", type="string", example="10 hours"),
     *     @OA\Property(property="game_id", type="integer", example=1),
     *     @OA\Property(property="platforms", type="array", @OA\Items(type="string", example="PS5")),
     *     @OA\Property(property="game_list_id", type="string", example="e2b2b3b4-5d6e-4f7a-8b9c-0d1e2f3a4b5c"),
     *     @OA\Property(property="status_id", type="integer", example=1)
     *  )
     * ),
     *     @OA\Response(
     *     response=201,
     *     description="Review created successfully"
     * ),
     *     @OA\Response(
     *     response=400,
     *     description="Failed to create review",
     *     @OA\JsonContent(
     *     @OA\Property(property="error", type="string", example="Invalid data provided")
     *     )
     *    )
     * )
     * )
     */

    public function create(array $data): array
    {
        $review = $this->model::create([
            'rating' => $data['rating'],
            'description' => $data['description'],
            'game_time' => $data['game_time'],
            'game_id' => Game::find($data['game_id']),
            'game_list_id' => GameList::find($data['game_list_id']),
            'status_id' => Status::find($data['status_id']),
        ]);

        $platforms = [];
        foreach ($data['platforms'] as $platform) {
            $platforms[] = Platform::findByName($platform->name);
        }
        $review->platforms()->attach($platforms);

        $review->save();

        return $review->toArray();
    }

}
