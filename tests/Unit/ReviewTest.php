<?php
namespace Tests\Unit;

use App\Repositories\ReviewRepository;
use Tests\TestCase;
use Mockery;

class ReviewTest extends TestCase
{
    public function testAddReview(): void
    {
        $reviewRepository = Mockery::mock(ReviewRepository::class);
        $reviewRepository->shouldReceive('create')->andReturn([
            'description' => "description",
            'gameListId' => 1,
            'gameId' => 1,
            'statusId' => 1,
        ]);

        $review = $reviewRepository->create([
            'description' => "description",
            'gameListId' => 1,
            'gameId' => 1,
            'statusId' => 1,
        ]);

        expect($review['description'])->toBe('description')
            ->and($review['gameListId'])->toBe(1)
            ->and($review['gameId'])->toBe(1)
            ->and($review['statusId'])->toBe(1);
    }

    public function testRemoveReview(): void
    {
        $reviewRepository = Mockery::mock(ReviewRepository::class);
        $reviewRepository->shouldReceive('delete')->andReturn(response('Successfully deleted', 200));

        $response = $reviewRepository->delete(1);

        expect($response->getContent())->toBe('Successfully deleted');
    }
}
