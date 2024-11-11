<?php

namespace Tests\Unit;
use App\Services\GameRecommendationService;
use App\Services\RecommendationSystem\Algorithm\CollaborativeRecommendation;
use App\Services\RecommendationSystem\Algorithm\ContentRecommendation;
use Tests\TestCase;
use Mockery;
class GameRecommendationServiceTest extends TestCase
{
    public function testFindGamesThatUserCanLike()
    {
        $collaborativeMock = Mockery::mock(CollaborativeRecommendation::class);
        $contentMock = Mockery::mock(ContentRecommendation::class);

        $collaborativeMock->shouldReceive('findGamesThatUserCanLike')
            ->with('user123')
            ->andReturn(['game1', 'game2']);

        $contentMock->shouldReceive('findGamesThatUserCanLike')
            ->with('user123')
            ->andReturn(['game3', 'game4']);

        $gameService = new GameRecommendationService($collaborativeMock, $contentMock);
        $recommendedGames = $gameService->findGamesThatUserCanLike('user123');

        $this->assertEquals(['game1', 'game2', 'game3', 'game4'], $recommendedGames);
    }
}
