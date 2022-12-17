<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemCategory extends Authenticatable
{
    use SoftDeletes, UsesUuid;

    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }
}
