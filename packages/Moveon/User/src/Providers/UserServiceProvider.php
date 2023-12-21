<?php

namespace Moveon\User\Providers;

use App\Models\User;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Moveon\User\Events\CreateAdminRoleEvent;
use Moveon\User\Events\FilterSeedEvent;
use Moveon\User\Events\SignUpUserUpdateEvent;
use Moveon\User\Listeners\CreateAdminRoleListener;
use Moveon\User\Listeners\FilterSeedListener;
use Moveon\User\Listeners\SignUpUserUpdateListener;
use Moveon\User\Policies\PermissionPolicy;
use Moveon\User\Policies\RolePolicy;
use Moveon\User\Policies\UserPolicy;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class       => UserPolicy::class,
        Role::class       => RolePolicy::class,
        Permission::class => PermissionPolicy::class,
    ];

    public function register(): void
    {
        # Register all listeners
        $this->registerListeners();
    }

    public function boot(): void
    {
        Route::group(['prefix' => 'api', 'middleware' => [SubstituteBindings::class]], function () {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
        });

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->registerPolicies();
    }

    /**
     * Assign listener
     * @return void
     */
    protected function registerListeners(): void
    {
        Event::listen(CreateAdminRoleEvent::class, CreateAdminRoleListener::class);
        Event::listen(SignUpUserUpdateEvent::class, SignUpUserUpdateListener::class);
        Event::listen(FilterSeedEvent::class, FilterSeedListener::class);
    }
}
