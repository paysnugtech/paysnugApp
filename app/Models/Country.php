<?php

namespace App\Models;

use App\Enums\CountryAvailableEnum;
use App\Models\Bank;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'countries';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $dates = ['deleted_at'];


    protected $fillable = [
        'id',
        'name',
        'currency',
        'currency_code',
        'is_available',
    ];
    
    protected $hidden = [
        'id',
        'is_available',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_available' => CountryAvailableEnum::class,
    ];


    public function addresses():HasMany
    {
        return $this->hasMany(Address::class);
    }


    public function wallet():HasOne
    {
        return $this->hasOne(Wallet::class);
    }


    public function banks():HasMany
    {
        return $this->hasMany(Bank::class);
    }
}
