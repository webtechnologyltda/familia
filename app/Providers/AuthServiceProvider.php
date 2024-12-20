<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('viewPulse', function (User $user) {
            return $user->hasRole(RoleEnum::SuperAdministrador->value);
        });

        Gate::define('audit', function (User $user) {
            return false;
        });

        Gate::define('restoreAudit', function (User $user) {
            return true;
        });
    }
}
