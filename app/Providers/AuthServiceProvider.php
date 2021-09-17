<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('store_update_offer', function ($user) {
            return $user->roles->first()->permissions->contains('name', "store_update_offer");
        });

        Gate::define('store_update_institution', function ($user) {
            return $user->roles->first()->permissions->contains('name', "store_update_institution");
        });

        Gate::define('store_update_apikey', function ($user) {
            return $user->roles->first()->permissions->contains('name', "store_update_apikey");
        });

        Gate::define('store_update_subscription', function ($user) {
            return $user->roles->first()->permissions->contains('name', "store_update_subscription");
        });

        Gate::define('store_update_user', function ($user) {
            return $user->roles->first()->permissions->contains('name', "store_update_user");
        });

        Passport::routes();
    }
}
