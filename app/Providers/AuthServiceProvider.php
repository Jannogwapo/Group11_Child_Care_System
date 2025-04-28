<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
// use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    // /**
    //  * The model to policy mappings for the application.
    //  *
    //  * @var array<class-string, class-string>
    //  */
    // protected $policies = [
    //     //
    // ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define admin gate
        Gate::define('isAdmin', function ($user) {
            dump($user);
            // return (int)$user->role_id === 2;
            return 1;
        });
    }
} 