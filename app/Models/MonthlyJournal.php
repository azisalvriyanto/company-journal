<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MonthlyJournal extends Model
{
    use SoftDeletes, UsesUuid;

    protected $guarded = [];

    public static function STATUSES()
    {
        return Status::query()->whereIn('name', [
            'Draft',
            'Lock',
        ])->get()->toArray();
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }
}
