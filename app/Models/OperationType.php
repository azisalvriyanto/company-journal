<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperationType extends Model
{
    use SoftDeletes, UsesUuid;

    public const GROUPS = [
        [
            'id'    => "In",
            'name'  => "In",
        ],
        [
            'id'    => "Out",
            'name'  => "Out",
        ],
    ];

    protected $guarded = [];
}
