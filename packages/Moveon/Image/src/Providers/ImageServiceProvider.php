<?php

namespace Moveon\Image\Providers;

use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Moveon\Image\Commands\CategorySeedCommand;
use Moveon\Image\Models\Image;
use Moveon\Image\Policies\ImagePolicy;

class ImageServiceProvider extends ServiceProvider
{
    protected $policies = [
        Image::class => ImagePolicy::class
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

    /**
     * Register package commands
     * @return void
     */
    public function registerCommands(): void
    {
        $this->commands([
            CategorySeedCommand::class
        ]);
    }
}
