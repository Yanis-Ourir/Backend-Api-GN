<?php

namespace App\Services\RecommendationSystem\Interface;

interface RecommendationAlgorithm
{
    public function filterEvaluationsOfUser(array $evaluations, string $userId): array;

}
