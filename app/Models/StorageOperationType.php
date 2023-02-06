<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StorageOperationType extends Model
{
    use SoftDeletes, UsesUuid;

    protected $guarded = [];

    public function storage()
    {
        return $this->belongsTo(User::class, 'storage_id', 'id');
    }

    public function operationType()
    {
        return $this->belongsTo(OperationType::class);
    }
}
