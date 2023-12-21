<?php

namespace Moveon\Tag\Providers;

use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Moveon\Tag\Models\Tag;
use Moveon\Tag\Policies\TagPolicy;

class TagServiceProvider extends ServiceProvider
{
    protected $policies = [
        Tag::class => TagPolicy::class
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        if ($this->app->runningInConsole()) {
            $this->registerCommands();
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        Route::group(['prefix' => 'api', 'middleware' => [SubstituteBindings::class]], function () {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
        });

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->registerPolicies();
    }

    public function registerCommands(): void
    {
        $this->commands([
        ]);
    }

}
