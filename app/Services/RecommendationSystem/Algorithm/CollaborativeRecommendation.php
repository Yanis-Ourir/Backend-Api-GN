<?php

namespace App\Services\RecommendationSystem\Algorithm;

use App\Repositories\EvaluationRepository;
use App\Repositories\GameRepository;

class CollaborativeRecommendation extends AbstractRecommendation
{

    public function sortRecommendedGames(array $recommendedGames): array
    {
        shuffle($recommendedGames);
        return array_splice($recommendedGames, 0, 3);
    }
    public function findGamesThatUserCanLike(string $userId): array
    {
        $gameIds = $this->evaluationRepository->filterUserEvaluations($userId); // RECUPERE LES EVALUATIONS DE L'UTILISATEUR > 7 ET RETURN LES ID DES JEUX ASSOCIES
        $evaluations = $this->evaluationRepository->findEvaluationsByGameIds($gameIds); // RECUPERE LES EVALUATIONS DE CES JEUX
        $filteredEvaluations = $this->filterEvaluationsOfUser($evaluations, $userId); // FILTRE LES EVALUATIONS DE L'UTILISATEUR (GARDE CEUX DES AUTRES UTILISATEURS > 7)
        $recommendedEvaluations = $this->evaluationRepository->filterMultipleUsersEvaluations($filteredEvaluations, $gameIds); // RECUPERE LES EVALUATIONS DES AUTRES UTILISATEURS PAR RAPPORT AUX JEUX QUE L'UTILISATEUR AVAIT NOTE
        $recommendedGames = $this->gameRepository->findGamesOfUserEvaluations($recommendedEvaluations); // RECUPERE LES JEUX RECOMMANDES
        return $this->sortRecommendedGames($recommendedGames); // RESSORT 3 JEUX RECOMMANDES SUR DU COLLABORATIVE
    }
}
