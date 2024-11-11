<?php
namespace Tests\Unit;
use App\Repositories\EvaluationRepository;
use App\Repositories\GameRepository;
use Mockery;
use Tests\TestCase;
use App\Services\RecommendationSystem\Algorithm\CollaborativeRecommendation;

class CollaborativeRecommendationTest extends TestCase
{
    public function testFindGamesThatUserCanLike()
    {
        $evaluationRepo = Mockery::mock(EvaluationRepository::class);
        $gameRepo = Mockery::mock(GameRepository::class);

        $evaluationRepo->shouldReceive('filterUserEvaluations')
            ->with('user1')
            ->andReturn(['game1', 'game2']);

        $evaluationRepo->shouldReceive('findEvaluationsByGameIds')
            ->with(['game1', 'game2'])
            ->andReturn([
                ['user_id' => 'user2', 'rating' => 8, 'game_id' => 'game1'],
                ['user_id' => 'user3', 'rating' => 9, 'game_id' => 'game2'],
            ]);

        $evaluationRepo->shouldReceive('filterMultipleUsersEvaluations')
            ->andReturn([
                ['user_id' => 'user4', 'game_id' => 'game3'],
            ]);

        $gameRepo->shouldReceive('findGamesOfUserEvaluations')
            ->with([
                ['user_id' => 'user4', 'game_id' => 'game3'],
            ])
            ->andReturn(['game3', 'game4', 'game5', 'game6']);

        $collaborativeRecommendation = new CollaborativeRecommendation($evaluationRepo, $gameRepo);
        $recommendedGames = $collaborativeRecommendation->findGamesThatUserCanLike('user1');

        $this->assertCount(3, $recommendedGames);
        $this->assertContains('game3', $recommendedGames);
    }
}
