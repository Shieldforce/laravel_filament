<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;

class PostObserver
{
    public function creating(Model $model)
    {
        $model->user_id = auth()->id();
    }
}
