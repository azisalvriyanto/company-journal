<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use SoftDeletes, UsesUuid,
    HasApiTokens, HasFactory, Notifiable, HasRoleAndPermission;

    public const GROUPS = [
        [
            'id'    => "Company",
            'name'  => "Company",
        ],
        [
            'id'    => "Storage",
            'name'  => "Storage",
        ],
        [
            'id'    => "User",
            'name'  => "User",
        ]
    ];

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function parentCompany()
    {
        return $this->belongsTo(User::class, 'parent_company_id', 'id')
        ->withDefault($this->find('fdcbff21-696b-4fbb-a2eb-19badda653b0')->toArray());
    }

    public function ownerTypes()
    {
        return $this->belongsToMany(OwnerType::class, 'user_owner_type');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'owner_id', 'id');
    }

    public function contactAddress()
    {
        return $this->belongsTo(Contact::class, 'default_contact_address_id', 'id');
    }

    public function contactAddresses()
    {
        return $this->hasMany(Contact::class, 'owner_id', 'id')->whereGroup('Contact');
    }

    public function billingAddress()
    {
        return $this->belongsTo(Contact::class, 'default_billing_address_id', 'id');
    }

    public function billingAddresses()
    {
        return $this->hasMany(Contact::class, 'owner_id', 'id')->whereGroup('Billing');
    }

    public function shippingAddress()
    {
        return $this->belongsTo(Contact::class, 'default_shipping_address_id', 'id');
    }

    public function shippingAddresses()
    {
        return $this->hasMany(Contact::class, 'owner_id', 'id')->whereGroup('Shipping');
    }

    public function storageOperationTypes()
    {
        return $this->hasMany(StorageOperationType::class, 'storage_id', 'id');
    }
}
