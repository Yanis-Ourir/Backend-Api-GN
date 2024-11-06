<?php

namespace App\Services\RecommendationSystem\Algorithm;

use App\Repositories\EvaluationRepository;
use App\Repositories\GameRepository;

class ContentRecommendation extends AbstractRecommendation
{
    public function retrieveGameTags(array $games): array {
        $countSimilarTags = [];

        foreach($games as $game) {
            foreach($game['tags'] as $tag) {
                if (array_key_exists($tag, $countSimilarTags)) {
                    $countSimilarTags[$tag]++;
                } else {
                    $countSimilarTags[$tag] = 1;
                }
            }
        }

        arsort($countSimilarTags);
        $sortedTags = array_slice($countSimilarTags, 0, 5);

        return array_keys($sortedTags);
    }

    public function findGamesThatUserCanLike(string $userId): array
    {
        $gameIds = $this->evaluationRepository->filterUserEvaluations($userId); // RECUPERER LES EVALUATIONS DE L'UTILISATEUR ET RETURN LES ID DES JEUX ASSOCIES
        $games = $this->gameRepository->findGamesOfUserEvaluations($gameIds); // RECUPERER LES JEUX
        $gameTagsCount = $this->retrieveGameTags($games); // RECUPERER LES TAGS DES JEUX ET TRIE PAR LES PLUS REVIEWED ET AIMER

        return $this->gameRepository->findGameByTags($gameTagsCount); // RESSORT LES JEUX RECOMMANDES SUR DU CONTENT BASED
    }
}
