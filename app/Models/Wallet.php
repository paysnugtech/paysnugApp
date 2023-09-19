<?php

namespace App\Models;

use App\Enums\WalletLimitedStatusEnum;
use App\Enums\WalletLockedStatusEnum;
use App\Models\WalletType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallet extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $dates = ['deleted_at'];


    protected $fillable = [
        'id',
        'user_id',
        'balance',
        'country_id',
        'is_limited',
        'is_locked',
        'wallet_type_id',
        'matured_at',
        'created_by',
        'updated_by'
    ];

    protected $hidden = [
        "id",
        'user_id',
        'wallet_type_id',
        'country_id',
        "created_by",
        "updated_by",
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_limited' => WalletLimitedStatusEnum::class,
        'is_locked' => WalletLockedStatusEnum::class
    ];


    public function accounts():HasMany
    {
        return $this->hasMany(Account::class);
    }


    public function country():BelongsTo
    {
        return $this->belongsTo(Country::class);
    }


    public function type():BelongsTo
    {
        return $this->belongsTo(WalletType::class, 'wallet_type_id');
    }


    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
