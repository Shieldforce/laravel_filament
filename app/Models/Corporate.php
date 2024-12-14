<?php

namespace App\Models;

use Filament\Models\Contracts\HasCurrentTenantLabel;
use Illuminate\Database\Eloquent\Model;

class Corporate extends Model implements HasCurrentTenantLabel
{
    protected $table    = 'corporates';

    protected $fillable = [
        "name",
        "document",
        "email",
        "phone",
        "slug",
        "domain",
    ];

    public function getCurrentTenantLabel(): string
    {
        return 'Corp. Ativada';
    }

    public function users() {
        return $this->belongsToMany(
            User::class,
            "corporate_user",
            "corporate_id",
            "user_id",
        )->withPivot([
            "corporate_id",
            "user_id",
        ]);
    }

    public function usersLogin()
    {
        return $this->hasMany(
            User::class,
            "corporate_latest_id",
            "id"
        );
    }
}
