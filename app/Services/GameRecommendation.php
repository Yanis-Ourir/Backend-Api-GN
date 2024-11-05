<?php

namespace App\Services;


use App\Repositories\EvaluationRepository;
use App\Repositories\GameRepository;


class GameRecommendation
{
    private EvaluationRepository $evaluationRepository;
    private GameRepository $gameRepository;
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

    // MISE EN CACHE > UNE DEMI-JOURNEE (12H)
    //  POURQUOI ? REFLEXION >= 7 / TROP PEU DE JEUX à 10, 6 est une note qui n'est pas mauvaise mais ni réellement bonne/ 7 est un bon jeu ou le persona à passé un bon moment
    public function findGamesThatUserCanLike(string $userId): array
    {
        $gameIds = $this->evaluationRepository->filterUserEvaluations($userId); // RECUPERE LES EVALUATIONS DE L'UTILISATEUR > 7 ET RETURN LES ID DES JEUX ASSOCIES
        $evaluations = $this->evaluationRepository->findEvaluationsByGameIds($gameIds); // RECUPERE LES EVALUATIONS DE CES JEUX
        $filteredEvaluations = $this->filterEvaluationsOfUser($evaluations, $userId); // FILTRE LES EVALUATIONS DE L'UTILISATEUR (GARDE CEUX DES AUTRES UTILISATEURS > 7)
        $recommendedEvaluations = $this->evaluationRepository->filterMultipleUsersEvaluations($filteredEvaluations, $gameIds); // RECUPERE LES EVALUATIONS DES AUTRES UTILISATEURS

        return $this->gameRepository->findGamesOfUserEvaluations($recommendedEvaluations);
    }
}
