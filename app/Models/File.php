<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table    = 'files';

    protected $fillable = [
        "name",
        "extension",
        "path",
        "filable_id",
        "filable_type",
    ];
}
