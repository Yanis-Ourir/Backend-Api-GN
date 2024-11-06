<?php

namespace App\Services\RecommendationSystem\Interface;

interface GameRecommendation
{
    public function findGamesThatUserCanLike(string $userId): array;
}
