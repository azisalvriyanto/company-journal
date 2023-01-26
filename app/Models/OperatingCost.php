<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperatingCost extends Model
{
    use SoftDeletes, UsesUuid;

    protected $guarded = [];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function unitOfMeasurement()
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'unit_of_measurement_id', 'id');
    }
}