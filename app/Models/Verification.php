<?php

namespace App\Models;

use App\Models\Bill;
use App\Models\Bvn;
use App\Models\Card;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Verification extends Model
{
    
    use HasFactory, HasUuids, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $dates = ['deleted_at'];

    protected $casts = [

    ];

    protected $fillable = [
        'id',
        'level',
        'attempt',
        'email_verified',
        'phone_verified',
        "user_id",
        'status',
        "updated_by",
    ];

    protected $hidden = [
        'id',
        'status',
        "user_id",
        "updated_by",
        'deleted_at',
        'created_at',
        'updated_at',
    ];


    public function bvn():HasOne
    {
        return $this->hasOne(Bvn::class);
    }


    public function bill():HasOne
    {
        return $this->hasOne(Bill::class);
    }


    public function card():HasOne
    {
        return $this->hasOne(Card::class);
    }


    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
