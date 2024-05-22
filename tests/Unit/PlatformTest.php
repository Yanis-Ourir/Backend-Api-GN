<?php
namespace Tests\Unit;
use App\Repositories\PlatformRepository;
use Mockery;
use Tests\TestCase;

class PlatformTest extends TestCase
{
    public function testAddPlatform(): void
    {
        $platformRepository = Mockery::mock(PlatformRepository::class);
        $platformRepository->shouldReceive('create')->andReturn([
            'name' => 'platform',
            'created_at' => '2022-01-01',
            'updated_at' => '2022-01-01',
        ]);

        $platform = $platformRepository->create([
            'name' => 'platform',
            'created_at' => '2022-01-01',
            'updated_at' => '2022-01-01',
        ]);

        expect($platform['name'])->toBe('platform')
            ->and($platform['created_at'])->toBe('2022-01-01')
            ->and($platform['updated_at'])->toBe('2022-01-01');
    }
    public function testRemovePlatform(): void
    {
        $platformRepository = Mockery::mock(PlatformRepository::class);
        $platformRepository->shouldReceive('delete')->andReturn(response('Successfully deleted', 200));

        $response = $platformRepository->delete(1);

        expect($response->getContent())->toBe('Successfully deleted');
    }
}
