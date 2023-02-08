<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperatingCostTransactionDetail extends Model
{
    use SoftDeletes, UsesUuid;

    protected $guarded = [];

    public function operatingCostTransaction()
    {
        return $this->belongsTo(OperatingCostTransaction::class);
    }

    public function operatingCost()
    {
        return $this->belongsTo(OperatingCost::class);
    }
}