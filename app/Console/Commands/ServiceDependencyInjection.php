<?php

namespace App\Console\Commands;

use App\Services\RecommendationSystem\Interface\GameRecommendationInterface;
use Illuminate\Console\Command;

class ServiceDependencyInjection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:injection-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(GameRecommendationInterface $gameRecommendation): void
    {
        $gameRecommendation->findGamesThatUserCanLike('9d28505b-4fee-4954-bf4d-2dff83798551');
    }
}
