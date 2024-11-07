<?php

namespace App\Services;

use App\Services\RecommendationSystem\Algorithm\CollaborativeRecommendation;
use App\Services\RecommendationSystem\Algorithm\ContentRecommendation;
use App\Services\RecommendationSystem\Interface\GameRecommendationInterface;


class GameRecommendationService implements GameRecommendationInterface
{

    // faire une interface pour chaque microservice qui implémente l'interface commune
    // faire une interface pour les méthodes communes
    private CollaborativeRecommendation $collaborativeRecommendation;
    private ContentRecommendation $contentRecommendation;

    public function __construct(CollaborativeRecommendation $collaborativeRecommendation, ContentRecommendation $contentRecommendation)
    {
        $this->collaborativeRecommendation = $collaborativeRecommendation;
        $this->contentRecommendation = $contentRecommendation;
    }

    public function findGamesThatUserCanLike(string $userId): array
    {
        $collaborativeGames = $this->collaborativeRecommendation->findGamesThatUserCanLike($userId);
        $contentGames = $this->contentRecommendation->findGamesThatUserCanLike($userId);

        return array_merge($collaborativeGames, $contentGames);
    }
}
