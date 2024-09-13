<?php

namespace Tests\Unit;

use App\Models\Evaluation;
use App\Models\Platform;
use App\Models\User;
use App\Repositories\EvaluationRepository;
use App\Repositories\PlatformRepository;
use App\Repositories\UserRepository;
use Mockery;
use Tests\TestCase;

class EvaluationTest extends TestCase
{
    public function testCreatingTest(): void
    {
        $evaluationRepository = Mockery::mock(EvaluationRepository::class);
        $evaluationRepository->shouldReceive('create')->andReturn([
            'rating' => 5,
            'description' => 'Super jeu',
            'gameTime' => '10 heures',
            'gameId' => 1,
            'statusId' => 1,
            'userId' => 1,
        ]);

        $evaluation = $evaluationRepository->create([
            'rating' => 5,
            'description' => 'Super jeu',
            'gameTime' => '10 heures',
            'gameId' => 1,
            'statusId' => 1,
            'userId' => 1,
        ]);

        expect($evaluation)->toBe([
            'rating' => 5,
            'description' => 'Super jeu',
            'gameTime' => '10 heures',
            'gameId' => 1,
            'statusId' => 1,
            'userId' => 1,
        ]);

    }

//    public function testInsertingEvaluationIntoDB()
//    {
//        $evaluationRepository = new EvaluationRepository(new Evaluation());
//        $evaluationRepository->create([
//            'rating' => 5,
//            'description' => 'Super jeu',
//            'game_time' => '10 heures',
//            'game_id' => 1,
//            'status_id' => 1,
//            'user_id' => '9ce9eb34-5218-40e2-9168-1efc268309a0',
//        ]);
//
//        $checkEvaluation = $evaluationRepository->findById(15);
//
//
//        expect($checkEvaluation)->toBe([
//            'id' => $checkEvaluation['id'],
//            'rating' => 5,
//            'description' => 'Super jeu',
//            'game_time' => '10 heures',
//            'game_id' => 1,
//            'status_id' => 1,
//            'user_id' => '9ce9eb34-5218-40e2-9168-1efc268309a0',
//            'created_at' => $checkEvaluation['created_at'],
//            'updated_at' => $checkEvaluation['updated_at'],
//        ]);
//    }

    public function testEvaluationNotFound(): void
    {
        $evaluationRepository = new EvaluationRepository(new Evaluation(), new PlatformRepository(new Platform()));
        $evaluation = $evaluationRepository->findById(1000);

        expect($evaluation)->toBe(["error" => "Not found"]);
    }

//    public function testCheckEvaluationAuthor(): void
//    {
//        $evaluationRepository = new EvaluationRepository(new Evaluation());
//        $userRepository = new UserRepository(new User());
//
//        $evaluation = $evaluationRepository->findById(3);
//        expect($evaluation)->toBe([
//            'id' => 3,
//            'rating' => 5,
//            'description' => 'Super jeu',
//            'game_time' => '10 heures',
//            'game_id' => 1,
//            'status_id' => 1,
//            'user_id' => '9c0e6287-8a50-46ad-97e6-88148fb08672',
//            'created_at' => '2024-05-22T13:14:30.000000Z',
//            'updated_at' => '2024-05-22T13:14:30.000000Z',
//        ]);
//
//        $user = $userRepository->findById($evaluation['user_id']);
//
//        expect($user['pseudo'])->toBe('Kraig Gleichner');
//
//    }

    public function testDeletingEvaluation(): void
    {
        $evaluationRepository = Mockery::mock(EvaluationRepository::class);
        $evaluationRepository->shouldReceive('delete')->andReturn(response('Successfully deleted', 200));

        $response = $evaluationRepository->delete(1);

        expect($response->getContent())->toBe('Successfully deleted');
    }

    // need to test the update method

    // need to test the delete method only on the evaluation that a user has created

}
