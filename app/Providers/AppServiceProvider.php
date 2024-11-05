<?php

namespace App\Providers;

use App\Models\Game;
use App\Persistance\Interface\PersistanceInterface;
use App\Persistance\PersistanceMySQL;
use App\Repositories\GameRepository;
use App\Repositories\Interface\RepositoryInterface;
use App\Services\ExternalsApi\Api\Rawgio;
use App\Services\ExternalsApi\Interface\ExternalApi;
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
//        $this->app->bind(GameRepository::class, function () {
//           return new PersistanceMySQL(new Game());
//        });
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
