<?php

namespace App\Repositories;

use App\Models\Game;
use App\Models\GameList;
use App\Models\Platform;
use App\Models\Review;
use App\Models\Status;
use Illuminate\Support\Facades\Log;
use OpenApi\Annotations as OA;

class ReviewRepository extends Repository
{
    private PlatformRepository $platformRepository;
    public function __construct(Review $model, PlatformRepository $platformRepository)
    {
        parent::__construct($model);
        $this->platformRepository = $platformRepository;
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
        try {
            $review = $this->model->updateOrCreate(
                [
                    'game_list_id' => $data['game_list_id'],
                    'game_id' => $data['game_id'],
                ],
                [
                    'description' => $data['description'],
                    'status_id' => $data['status_id'],
                ]
            );
        } catch (\Exception $e) {
            Log::error('Database query error: ' . $e->getMessage());
            throw new \Exception('Failed to create review');
        }

        return $review->toArray();
    }

    public function update(int|string $id, array $data): array
    {
        $review = $this->model->find($id);

        if (!$review) {
            return ['error' => 'Review not found'];
        }

        try {
            $review->update($data);
        } catch (\Exception $e) {
            throw new \Exception('Failed to update review');
        }

        return $review->toArray();
    }
}
