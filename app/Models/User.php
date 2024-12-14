<?php

namespace App\Models;

use App\Observers\UserObserver;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasDefaultTenant;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

class User extends Authenticatable implements FilamentUser, HasTenants, HasDefaultTenant
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected static function boot()
    {
        parent::boot();

        self::observe(UserObserver::class);
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'system',
        "corporate_login_id",
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'user_role',
            'user_id',
            'role_id',
        )->withPivot([
            'user_id',
            'role_id',
        ]);
    }

    /*
     * ACL
     */

    public function hasAnyRoles($roles): bool
    {
        if (is_object($roles)) {
            return !!$roles->intersect($this->roles)->count();
        }

        return $this->roles->contains('name', $roles);
    }

    public function hasPermission(Permission $permission): bool
    {
        return $this->hasAnyRoles($permission->roles);
    }

    /*
     * MultiTenant
     */

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->corporates()->whereKey($tenant)->exists();
    }

    public function getTenants(Panel $panel): array|Collection
    {
        return $this->corporates;
    }

    public function latestCorporate()
    {
        return $this->hasOne(
            Corporate::class,
            "id",
            "corporate_latest_id"
        );
    }

    public function corporates()
    {
        return $this->belongsToMany(
            Corporate::class,
            "corporate_user",
            "user_id",
            "corporate_id",
        )->withPivot([
            "user_id",
            "corporate_id",
        ]);
    }

    public function getDefaultTenant(Panel $panel): ?Model
    {
        return $this->latestCorporate;
    }
}
