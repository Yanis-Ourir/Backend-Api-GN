<?php

namespace App\Services\RecommendationSystem\Interface;

interface RecommendationAlgorithmInterface
{
    public function filterEvaluationsOfUser(array $evaluations, string $userId): array;
    public function findGamesThatUserCanLike(string $userId): array;
}
