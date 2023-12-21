<?php

namespace Moveon\Setting\Providers;

use App\Models\User;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Moveon\Platform\Models\Platform;
use Moveon\Setting\Events\SendOtpEvent;
use Moveon\Setting\Listeners\SendOtpListener;
use Moveon\Setting\Models\Integration;
use Moveon\Setting\Models\Lead;
use Moveon\Setting\Policies\AccountPolicy;
use Moveon\Setting\Policies\IntegrationPolicy;
use Moveon\Setting\Policies\LeadPolicy;
use Moveon\Setting\Policies\UserSettingPolicy;

class SettingServiceProvider extends ServiceProvider
{
    protected $policies = [
        Platform::class    => AccountPolicy::class,
        Integration::class => IntegrationPolicy::class,
        Lead::class        => LeadPolicy::class,
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

        $this->loadViewsFrom(__DIR__ . '/../Resources/Views', 'Moveon.Setting');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->registerPolicies();

        $this->publishes([
            __DIR__ . '/../Resources/Views' => resource_path('views/Moveon/Setting'),
        ], 'views');
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
        Event::listen(SendOtpEvent::class, SendOtpListener::class);
    }
}
