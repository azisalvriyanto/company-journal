<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentTerm extends Model
{
    use SoftDeletes, UsesUuid;

    public const DEADLINE_TYPES = [
        [
            'id'    => "Day",
            'name'  => "Day",
        ],
        [
            'id'    => "Month",
            'name'  => "Month",
        ],
        [
            'id'    => "Year",
            'name'  => "Year",
        ]
    ];

    protected $guarded = [];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }
}
