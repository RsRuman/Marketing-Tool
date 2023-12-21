<?php

namespace Moveon\Conversation\Providers;

use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Moveon\EmailTemplate\Models\EmailTemplate;
use Moveon\EmailTemplate\Models\FeatureEmailTemplate;
use Moveon\EmailTemplate\Policies\EmailTemplatePolicy;
use Moveon\EmailTemplate\Policies\FeatureEmailTemplatePolicy;

class ConversationServiceProvider extends ServiceProvider
{
    protected $policies = [
        EmailTemplate::class => EmailTemplatePolicy::class,
        FeatureEmailTemplate::class => FeatureEmailTemplatePolicy::class,
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
        #
    }
}
