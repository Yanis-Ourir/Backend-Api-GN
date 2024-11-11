<?php

namespace Tests\Unit;

use App\Repositories\EvaluationRepository;
use App\Repositories\GameRepository;
use App\Services\RecommendationSystem\Algorithm\ContentRecommendation;
use Tests\TestCase;
use Mockery;

class ContentRecommendationTest extends TestCase
{
    public function testRetrieveGameTags()
    {
        $evaluationRepo = Mockery::mock(EvaluationRepository::class);
        $gameRepo = Mockery::mock(GameRepository::class);
        $contentRecommendation = new ContentRecommendation($evaluationRepo, $gameRepo);

        $games = [
            ['tags' => ['action', 'rpg', 'adventure']],
            ['tags' => ['action', 'rpg']],
            ['tags' => ['adventure', 'puzzle']],
        ];

        $tags = $contentRecommendation->retrieveGameTags($games);
        $this->assertEquals(['action', 'rpg', 'adventure', 'puzzle'], $tags);
    }

    public function testFindGamesThatUserCanLike()
    {
        $evaluationRepo = Mockery::mock(EvaluationRepository::class);
        $gameRepo = Mockery::mock(GameRepository::class);
        $contentRecommendation = new ContentRecommendation($evaluationRepo, $gameRepo);

        $evaluationRepo->shouldReceive('filterUserEvaluations')
            ->with('user123')
            ->andReturn(['game1', 'game2']);

        $gameRepo->shouldReceive('findGamesOfUserEvaluations')
            ->with(['game1', 'game2'])
            ->andReturn([
                ['tags' => ['action', 'rpg']],
                ['tags' => ['adventure']],
            ]);

        $gameRepo->shouldReceive('findGameByTags')
            ->with(['action', 'rpg', 'adventure'])
            ->andReturn(['game3', 'game4']);


        $recommendedGames = $contentRecommendation->findGamesThatUserCanLike('user123');
        $this->assertEquals(['game3', 'game4'], $recommendedGames);
    }


}
