<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('browse-patients', function ($user) {

            if (auth()->user()->role_id == 5){
                return false;
            } else {
                return true;
            }

        });

        Gate::define('root-admin', function ($user) {

            if (auth()->user()->role_id == 1 ){
                return true;
            }

        });
    }
}
