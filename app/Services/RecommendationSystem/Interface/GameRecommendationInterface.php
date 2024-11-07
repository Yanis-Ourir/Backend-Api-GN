<?php

namespace App\Services\RecommendationSystem\Interface;

interface GameRecommendationInterface
{
    public function findGamesThatUserCanLike(string $userId): array;
}
