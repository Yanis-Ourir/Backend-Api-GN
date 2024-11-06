<?php

namespace App\Providers;

use App\Repositories\EvaluationRepository;
use App\Repositories\GameRepository;
use App\Services\ExternalsApi\Api\Rawgio;
use App\Services\ExternalsApi\Interface\ExternalApi;
use App\Services\GameRecommendationService;
use App\Services\RecommendationSystem\Algorithm\CollaborativeRecommendation;
use App\Services\RecommendationSystem\Algorithm\ContentRecommendation;
use App\Services\RecommendationSystem\Interface\GameRecommendation;
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

        $this->app->bind(ExternalApi::class, Rawgio::class);
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
