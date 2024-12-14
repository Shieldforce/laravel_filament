<?php

namespace App\Models;

use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasCurrentTenantLabel;
use Illuminate\Database\Eloquent\Model;

class Corporate extends Model implements HasAvatar, HasCurrentTenantLabel
{
    protected $table    = 'corporates';

    protected $fillable = [
        "name",
        "document",
        "email",
        "phone",
        "slug",
        "domain",
        "avatar_url",
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

    public function latestsUsers()
    {
        return $this->hasMany(
            User::class,
            "corporate_latest_id",
            "id"
        );
    }

    public function getFilamentAvatarUrl(): ?string
    {
        if ($this->avatar_url) {
            return asset('storage/' . $this->avatar_url);
        } /*else {
            return asset('img/logo-default.png');
        }*/
    }
}
