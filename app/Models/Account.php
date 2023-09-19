<?php

namespace App\Models;

use App\Enums\UserStatusEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $dates = ['deleted_at'];


    protected $fillable = [
        'number',
        'bank_id',
        'user_id',
        'wallet_id',
        'is_active',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_active' => UserStatusEnum::class,
    ];

    protected $hidden = [
        'id',
        'bank_id',
        'wallet_id',
        'user_id',
        "is_active",
        "created_by",
        "updated_by",
        "deleted_at",
        'created_at',
        'updated_at',
    ];


    public function bank():BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }


    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function wallet():BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }
}
