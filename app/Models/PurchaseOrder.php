<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use SoftDeletes, UsesUuid;

    protected $guarded = [];

    public static function STATUSES()
    {
        return Status::query()->whereIn('name', [
            'Draft',
            'Quotation',
            'Close',
            'Cancel',
        ])->whereIsEnable(TRUE)->get()->toArray();
    }

    public function monthlyJournal()
    {
        return $this->belongsTo(MonthlyJournal::class);
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id', 'id');
    }

    public function vendorAddress()
    {
        return $this->belongsTo(Contact::class, 'vendor_address_id', 'id');
    }

    public function paymentTerm()
    {
        return $this->belongsTo(PaymentTerm::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function billingItems()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}