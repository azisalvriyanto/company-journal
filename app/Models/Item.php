<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes, UsesUuid;

    public const DETAIL_GROUPS = [
        [
            'id'    => "Product Name",
            'name'  => "Product Name",
        ],
        [
            'id'    => "Production Date",
            'name'  => "Production Date",
        ],
        [
            'id'    => "Expired Date",
            'name'  => "Expired Date",
        ]
    ];

    protected $guarded = [];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function unitOfMeasurement()
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'unit_of_measurement_id', 'id');
    }

    public function itemScanningCodes()
    {
        return $this->hasMany(ItemScanningCode::class);
    }
}