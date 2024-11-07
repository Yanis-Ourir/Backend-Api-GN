<?php

namespace App\Providers;

use App\Repositories\EvaluationRepository;
use App\Repositories\GameRepository;
use App\Services\ExternalsApi\Api\Rawgio;
use App\Services\ExternalsApi\Interface\ExternalApiInterface;
use App\Services\GameRecommendationService;
use App\Services\RecommendationSystem\Algorithm\CollaborativeRecommendation;
use App\Services\RecommendationSystem\Algorithm\ContentRecommendation;
use App\Services\RecommendationSystem\Interface\GameRecommendationInterface;
use App\Services\RecommendationSystem\Interface\RecommendationAlgorithmInterface;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ExternalApiInterface::class, Rawgio::class);
//        new GameRecommendationService(new CollaborativeRecommendation($this->evaluationRepository, $this), new ContentRecommendation($this->evaluationRepository, $this));


//        $this->app->bind(GameRecommendationInterface::class, function($app) {
//            return new GameRecommendationService(
//                $app->make(CollaborativeRecommendation::class),
//                $app->make(ContentRecommendation::class)
//            );
//        });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if(App::environment() === 'production') {
            URL::forceScheme('https');
        }
    }
}
