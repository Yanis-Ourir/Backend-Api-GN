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
    private Game $modelGame;
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
       $games = $this->gameRepository->findGamesOfCurrentUserLastEvaluations($userId); // RECOIS LES 10 DERNIERS JEUX QU'IL A NOTE > 7
       $evaluations = $this->evaluationRepository->findEvaluationsByGameIds($games); // RECUPERE LES EVALUATIONS DE CES JEUX
       $filteredEvaluations = $this->filterEvaluationsOfUser($evaluations, $userId); // FILTRE LES EVALUATIONS DE L'UTILISATEUR (GARDE CEUX DES AUTRES UTILISATEURS > 7)

       // RECUPERER LES JEUX EVALUES PAR LES UTILISATEURS QUI ONT NOTE LES MEMES JEUX AVEC DES NOTES SIMILAIRES > 7
       return $this->gameRepository->findGamesOfCurrentUserLastEvaluations($filteredEvaluations[0]['user_id']);
    }
}
