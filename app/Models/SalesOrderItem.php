<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesOrderItem extends Model
{
    use SoftDeletes, UsesUuid;

    protected $guarded = [];

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}