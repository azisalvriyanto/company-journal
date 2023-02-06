<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemScanningCode extends Model
{
    use SoftDeletes, UsesUuid;

    protected $guarded = [];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
