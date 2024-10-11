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


    public function findEvaluationsOfUsersWhoRatedSameGames(array $users): array
    {
        $evaluations = [];
        foreach($users as $user) {
            $evaluations[] = collect($this->evaluationRepository->findEvaluationsByUserId($user))->filter(function ($evaluation) {
                return $evaluation['rating'] > 7;
            })->values();
        }
        return $evaluations;
    }

    public function excludeGamesAlreadyEvaluatedByUser(array $evaluations, string $userId): array
    {
        $evaluations = collect($evaluations)->reject(function ($evaluation) use ($userId) {
            return $evaluation['user_id'] == $userId;
        })->values();


        return $evaluations->toArray();
    }

    public function filterGamesByEvaluation(array $evaluations): array
    {
        $games = [];
        foreach($evaluations as $evaluation) {
            $games[] = $this->gameRepository->findById($evaluation['game_id']);
        }
        return $games;
    }

    public function findGamesThatUserCanLike(string $userId): array
    {
       $games = $this->gameRepository->findGamesOfCurrentUserLastEvaluations($userId); // RECOIS LES 10 DERNIERS JEUX QU'IL A NOTE > 7
       $evaluations = $this->evaluationRepository->findEvaluationsByGameIds($games); // RECUPERE LES EVALUATIONS DE CES JEUX
       dd($evaluations);
       // RECUPERER LES UTILISATEURS QUI ONT NOTE LES MEMES JEUX AVEC DES NOTES SIMILAIRES > 7
        //RECUPERER LEURS AUTRES BONNES EVALUATIONS
        // ENVOYER CES JEUX A NOTRE UTILISATEUR
       $evaluationsFiltered = $this->excludeGamesAlreadyEvaluatedByUser($evaluations, $userId);
       return $this->filterGamesByEvaluation($evaluationsFiltered);
    }
}
