<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [

    ];

  
    public function register(): void
    {
        
    }


    public function boot(): void
    {
        $this->registerPolicies();
        Gate::define('isAdmin', function ($user) {
            return $user->role_id === 1;
        });
        
        Gate::define('Access', function ($user) {
            return $user->access_id === 2;
        });

    }
}
