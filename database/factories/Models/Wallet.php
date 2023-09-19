<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'is_limited'
    ];


    public function country():BelongsTo
    {
        return $this->belongsTo(Country::class);
    }


    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
