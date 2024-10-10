<?php

namespace App\Repositories;

use App\Models\Evaluation;
use App\Models\Game;
use App\Models\GameList;
use App\Models\Platform;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

class EvaluationRepository extends Repository
{
    private PlatformRepository $platformRepository;
    private Game $modelGame;
    public function __construct(Evaluation $model, Game $modelGame, PlatformRepository $platformRepository)
    {
        parent::__construct($model);
        $this->platformRepository = $platformRepository;
        $this->modelGame = $modelGame;
    }

    /**
     * @OA\Post(
     *     path="/evaluations",
     *     tags={"evaluations"},
     *     summary="Create a new evaluation",
     *     description="Create a new evaluation, such as a review or a rating",
     *     @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *     required={"rating", "description", "game_time", "game_id", "status_id", "user_id"},
     *     @OA\Property(property="rating", type="integer", example=10),
     *     @OA\Property(property="description", type="string", example="This game is amazing!"),
     *     @OA\Property(property="game_time", type="integer", example=30),
     *     @OA\Property(property="game_id", type="integer", example=1),
     *     @OA\Property(property="platforms", type="array", @OA\Items(type="string", example="PS5")),
     *     @OA\Property(property="status_id", type="integer", example=1),
     *     @OA\Property(property="user_id", type="string", example="e2a7b4b3-7b3b-4b3b-8b3b-2b3b7b3b7b3b")
     *  )
     * ),
     *     @OA\Response(
     *     response=201,
     *     description="Evaluation created successfully"
     * ),
     *     @OA\Response(
     *     response=400,
     *     description="Failed to create evaluation",
     *     @OA\JsonContent(
     *     @OA\Property(property="error", type="string", example="Invalid data provided")
     *     )
     *    )
     * )
     * )
     */

    public function create(array $data): array
    {

        $evaluation = $this->model->create([
            'rating' => $data['rating'],
            'description' => $data['description'],
            'game_time' => $data['game_time'],
            'game_id' => $data['game_id'],
            'status_id' => $data['status_id'],
            'user_id' => $data['user_id'],
        ]);


        $platforms = $this->platformRepository->findByName($data['platforms']);

        foreach($platforms as $platform) {
            $evaluation->platforms()->attach($platform['id']);
        }

        $evaluation->save();

        $this->updateGameRatingByEvaluation($data['game_id']);

        return $evaluation->toArray();
    }

    public function findEvaluationsByGameId(int $gameId): array
    {
        $evaluations = $this->model->where('game_id', $gameId)->get();

        if (!$evaluations) {
            return ['error' => 'No evaluations found for this game'];
        }

        // Récupére les plateformes de chaque évaluation
        foreach($evaluations as $evaluation) {
            $platforms = $evaluation->platforms()->get();
            $evaluation['platforms'] = $platforms;
            $statuses = $evaluation->status()->get();
            $evaluation['status'] = $statuses;
        }


        return $evaluations->toArray();
    }

    private function updateGameRatingByEvaluation(int $gameId): void
    {
        $evaluations = $this->model->where('game_id', $gameId)->get();
        $gameRating = 0;
        $nbEvaluations = 0;

        foreach($evaluations as $evaluation) {
            $gameRating += $evaluation['rating'];
            $nbEvaluations++;
        }

        $gameRating = $gameRating / $nbEvaluations;

        $game = $this->modelGame->find($gameId);
        $game->rating = $gameRating;
        $game->save();
    }

}
