<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        //
    ];

    public function register(): void
    {
        //
    }

    public function boot(Gate $gate): void
    {
        $this->registerPolicies();

        $gate->before(function ($user, $ability) {
            if ($user->hasAnyRoles('Admin')) {
                return true;
            };
        });

        $permissions = Permission::all();

        foreach ($permissions as $permission) {
            $gate->define($permission->name, function (User $user) use ($permission) {
                return $user->hasPermission($permission);
            });
        }
    }
}
