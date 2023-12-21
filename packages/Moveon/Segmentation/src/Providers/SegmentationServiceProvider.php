<?php

namespace Moveon\Segmentation\Providers;

use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Moveon\Segmentation\Models\UserSegmentation;
use Moveon\Segmentation\Policies\SegmentationPolicy;

class SegmentationServiceProvider extends ServiceProvider
{
    protected $policies = [
        UserSegmentation::class => SegmentationPolicy::class
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
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
}
