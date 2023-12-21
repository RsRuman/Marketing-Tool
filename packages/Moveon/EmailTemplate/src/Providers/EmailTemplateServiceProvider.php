<?php

namespace Moveon\EmailTemplate\Providers;

use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Str;
use Moveon\EmailTemplate\Commands\FeatureTemplateSeedCommand;
use Moveon\EmailTemplate\Models\EmailTemplate;
use Moveon\EmailTemplate\Models\FeatureEmailTemplate;
use Moveon\EmailTemplate\Policies\EmailTemplatePolicy;
use Moveon\EmailTemplate\Policies\FeatureEmailTemplatePolicy;

class EmailTemplateServiceProvider extends ServiceProvider
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

        Str::macro('replacePlaceholder', function ($originalStr, $placeholders) {
            foreach ($placeholders as $placeholder) {
                $originalStr = Str::replace(key($placeholder), $placeholder[key($placeholder)], $originalStr);
            }
            return $originalStr;
        });
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
            FeatureTemplateSeedCommand::class
        ]);
    }
}
