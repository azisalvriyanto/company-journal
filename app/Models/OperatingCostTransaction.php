<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperatingCostTransaction extends Model
{
    use SoftDeletes, UsesUuid;

    protected $guarded = [];

    public static function STATUSES()
    {
        return Status::query()->whereIn('name', [
            'Draft',
            'Cancel',
            'Lock',
            'Close',
        ])->get()->keyBy('name')->toArray();
    }

    public function monthlyJournal()
    {
        return $this->belongsTo(MonthlyJournal::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    // public function supplier()
    // {
    //     return $this->belongsTo(User::class, 'supplier_id', 'id');
    // }

    // public function supplierAddress()
    // {
    //     return $this->belongsTo(Contact::class, 'supplier_address_id', 'id');
    // }

    // public function paymentTerm()
    // {
    //     return $this->belongsTo(PaymentTerm::class);
    // }
}