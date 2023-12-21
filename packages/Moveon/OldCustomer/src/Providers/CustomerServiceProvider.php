<?php

namespace Moveon\Customer\Providers;

use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Moveon\Customer\Commands\FilterCriteriaSeedCommand;
use Moveon\Customer\Commands\FilterSeedCommand;
use Moveon\Customer\Commands\GroupSeedCommand;
use Moveon\Customer\Models\Customer;
use Moveon\Customer\Models\Segmentation;
use Moveon\Customer\OldPolicies\CustomerPolicy;
use Moveon\Customer\OldPolicies\LeadPolicy;
use Moveon\Customer\OldPolicies\SegmentationPolicy;
use Moveon\Setting\Models\Lead;

class CustomerServiceProvider extends ServiceProvider
{
    protected $policies = [
        Customer::class => CustomerPolicy::class,
        Segmentation::class => SegmentationPolicy::class,
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
//            GroupSeedCommand::class,
//            FilterSeedCommand::class,
//            FilterCriteriaSeedCommand::class
        ]);
    }

}
