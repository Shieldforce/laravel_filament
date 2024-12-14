<?php

namespace App\Providers;

use App\Models\Corporate;
use App\Models\Permission;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Config;

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
        $domain    = $_SERVER["HTTP_HOST"] ?? null;
        $corporate = Corporate::where('domain', $domain)->first();

        $gate->before(function ($user, $ability) use ($corporate) {
            $tenant = $corporate ?? Filament::getTenant();

            if (isset($tenant->domain)) {
                Config::set("APP_URL", $tenant->domain);
            }

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
