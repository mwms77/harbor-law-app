<?php

namespace App\Providers;

use App\Models\ClientUpload;
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
        // Clients can only view their own uploads, admins can view all
        Gate::define('view-upload', function (User $user, ClientUpload $upload) {
            return $user->id === $upload->user_id || $user->is_admin;
        });

        // Only admins can delete uploads
        Gate::define('delete-upload', function (User $user) {
            return $user->is_admin;
        });

        // Only admins can view admin panel
        Gate::define('access-admin', function (User $user) {
            return $user->is_admin;
        });
    }
}
