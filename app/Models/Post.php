<?php

namespace App\Models;

use App\Observers\PostObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    protected $table = 'posts';

    protected $fillable = [
        "title",
        "description",
        "user_id",
    ];


    protected static function boot()
    {
        parent::boot();

        self::observe(PostObserver::class);
    }

    public function files()
    {
        return $this->hasMany(
            File::class,
            "filable_id",
            "id",
        )->where("filable_type", Post::class);
    }
}
