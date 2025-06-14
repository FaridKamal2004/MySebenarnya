<?php

namespace App\Providers;

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
        $this->registerPolicies();

        // Define gates for roles
        Gate::define('mcmc', function ($user) {
            return $user->hasRole('mcmc');
        });

        Gate::define('agency', function ($user) {
            return $user->hasRole('agency');
        });

        Gate::define('public', function ($user) {
            return $user->hasRole('public');
        });
        
        // Define gates for permissions
        Gate::define('manage agencies', function ($user) {
            return $user->hasRole('mcmc');
        });
    }
}