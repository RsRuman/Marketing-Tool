<?php

namespace Moveon\Lead\Providers;

use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class LeadServiceProvider extends ServiceProvider
{
    protected $policies = [
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

        # Register all listeners
        $this->registerListeners();
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

        $this->registerPolicies();
    }

    /**
     * Register package commands
     * @return void
     */
    public function registerCommands(): void
    {
        $this->commands([
        ]);
    }

    /**
     * Assign listener
     * @return void
     */
    protected function registerListeners(): void
    {
    }
}
