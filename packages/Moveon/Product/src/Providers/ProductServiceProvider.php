<?php

namespace Moveon\Product\Providers;

use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Moveon\Customer\Models\Product;
use Moveon\Product\Policies\ProductPolicy;

class ProductServiceProvider extends ServiceProvider
{
    protected $policies = [
        Product::class => ProductPolicy::class
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
}
