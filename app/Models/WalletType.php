<?php

namespace App\Models;

use App\Enums\WalletTypeStatusEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class WalletType extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'wallet_types';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $dates = ['deleted_at'];

    protected $casts = [
        "status" => WalletTypeStatusEnum::class
    ];

    protected $hidden = [
        "id",
        "status",
        "created_by",
        "updated_by",
        "deleted_at",
        "created_at",
        "updated_at"
    ];

    protected $fillable = [
        'id',
        'name',
        'description',
        'status',
        'created_by',
        'updated_by'
    ];



    public function wallets():HasMany
    {
        return $this->hasMany(Wallet::class);
    }
}
