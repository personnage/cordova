<?php

namespace App\Providers;

use Carbon\Carbon;
use Laravel\Passport\Passport;
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
        // \App\Models\User::class => \App\Policies\UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->passport();

        $this->gates();
    }

    protected function gates()
    {
        Gate::before(function ($user) {
            return (bool) $user->admin;
        });

        Gate::define('destroy-user', function ($user) {
            return false;
        });

        Gate::define('delete-user', function ($user) {
            return false;
        });

        Gate::define('restore-user', function ($user) {
            return false;
        });
    }

    protected function passport()
    {
        // Next, you should call the Passport::routes method within the boot method
        // of your AuthServiceProvider. This method will register the routes
        // necessary to issue access tokens and revoke access tokens, clients,
        // and personal access tokens.
        Passport::routes();

        // This method will not delete all revoked tokens immediately.
        // Instead, revoked tokens will be deleted when a user requests
        // a new access token or refreshes an existing token.
        Passport::pruneRevokedTokens();

        // Passport::tokensExpireIn(Carbon::now()->addMinutes(10));

        // Passport::refreshTokensExpireIn(Carbon::now()->addMinutes(30));
    }
}
