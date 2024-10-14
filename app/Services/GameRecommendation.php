<?php

namespace App\Services;

use App\Models\Game;
use App\Repositories\EvaluationRepository;
use App\Repositories\GameRepository;
use Ramsey\Collection\Collection;

class GameRecommendation
{
    private EvaluationRepository $evaluationRepository;
    private GameRepository $gameRepository;
    public function __construct(EvaluationRepository $evaluationRepository, GameRepository $gameRepository, Game $modelGame)
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


    public function findGamesThatUserCanLike(string $userId): array
    {
        $gameIds = $this->evaluationRepository->filterUserEvaluations($userId); // RECUPERE LES EVALUATIONS DE L'UTILISATEUR > 7 ET RETURN LES ID DES JEUX ASSOCIES
        $evaluations = $this->evaluationRepository->findEvaluationsByGameIds($gameIds); // RECUPERE LES EVALUATIONS DE CES JEUX
        $filteredEvaluations = $this->filterEvaluationsOfUser($evaluations, $userId); // FILTRE LES EVALUATIONS DE L'UTILISATEUR (GARDE CEUX DES AUTRES UTILISATEURS > 7)
        $recommendedEvaluations = $this->evaluationRepository->filterMultipleUsersEvaluations($filteredEvaluations); // RECUPERE LES EVALUATIONS DES AUTRES UTILISATEURS

        return $this->gameRepository->findGamesOfUserEvaluations($recommendedEvaluations);
    }
}
