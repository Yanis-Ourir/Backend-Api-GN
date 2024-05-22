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
            'userId' => 1,
            'rating' => 8,
            'description' => "description",
            'gameTime' => "100h",
            'statusId' => 1,
            'reviewableType' => "App\Models\GameList",
            'reviewableId' => 1,
        ]);

        $review = $reviewRepository->create([
            'userId' => 1,
            'rating' => 8,
            'description' => "description",
            'gameTime' => "100h",
            'statusId' => 1,
            'reviewableType' => "App\Models\GameList",
            'reviewableId' => 1,
        ]);

        expect($review['userId'])->toBe(1)
            ->and($review['rating'])->toBe(8)
            ->and($review['description'])->toBe("description")
            ->and($review['gameTime'])->toBe("100h")
            ->and($review['statusId'])->toBe(1)
            ->and($review['reviewableType'])->toBe("App\Models\GameList")
            ->and($review['reviewableId'])->toBe(1);
    }

    public function testRemoveReview(): void
    {
        $reviewRepository = Mockery::mock(ReviewRepository::class);
        $reviewRepository->shouldReceive('delete')->andReturn(response('Successfully deleted', 200));

        $response = $reviewRepository->delete(1);

        expect($response->getContent())->toBe('Successfully deleted');
    }
}
