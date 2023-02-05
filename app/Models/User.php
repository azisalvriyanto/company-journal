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

    public function ownerGroups()
    {
        return $this->belongsToMany(OwnerGroup::class, 'owner_owner_group', 'owner_id', 'owner_group_id');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'owner_id', 'id');
    }
}
