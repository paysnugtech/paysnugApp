<?php

namespace App\Models;

use App\Enums\UserStatusEnum;
use App\Enums\VirtualAccountEnum;
use App\Models\Country;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
    
    use HasFactory, HasUuids, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $dates = ['deleted_at'];


    protected $fillable = [
        'name',
        'bank_code',
        'address',
        'type',
        'is_virtual_account',
        'is_active',
        'country_id',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_active' => UserStatusEnum::class,
        'is_virtual_account' => VirtualAccountEnum::class,
    ];

    protected $hidden = [
        'id',
        'bank_id',
        "is_active",
        "is_virtual_account",
        "country_id",
        "created_by",
        "updated_by",
        "deleted_at",
        'created_at',
        'updated_at',
    ];


    public function accounts():HasMany
    {
        return $this->hasMany(Bank::class);
    }


    public function country():BelongsTo
    {
        return $this->BelongsTo(Country::class);
    }
}
