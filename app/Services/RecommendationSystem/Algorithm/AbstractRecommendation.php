<?php

namespace App\Services\RecommendationSystem\Algorithm;

use App\Repositories\EvaluationRepository;
use App\Repositories\GameRepository;
use App\Services\RecommendationSystem\Interface\RecommendationAlgorithmInterface;

abstract class AbstractRecommendation
{
    protected EvaluationRepository $evaluationRepository;
    protected GameRepository $gameRepository;

    public function __construct(EvaluationRepository $evaluationRepository, GameRepository $gameRepository)
    {
        $this->evaluationRepository = $evaluationRepository;
        $this->gameRepository = $gameRepository;
    }

    public function filterEvaluationsOfUser(array $evaluations, string $userId): array
    {
        $evaluations = collect($evaluations)->reject(function ($evaluation) use ($userId) {
            return $evaluation['user_id'] == $userId;
        })->values();

        $evaluations = collect($evaluations)->reject(function ($evaluation) {
            return $evaluation['rating'] < 7;
        })->values();

        return $evaluations->toArray();
    }

    abstract public function findGamesThatUserCanLike(string $userId): array;

}
