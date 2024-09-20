<?php

namespace App\Providers;

use App\Models\Game;
use App\Persistance\Interface\PersistanceInterface;
use App\Persistance\PersistanceMySQL;
use App\Repositories\GameRepository;
use App\Repositories\Interface\RepositoryInterface;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
