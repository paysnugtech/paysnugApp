<?php

namespace App\Models;

use App\Models\Transaction;
use App\Models\User;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Electricity extends Model
{
    
    use HasFactory, Notifiable, SoftDeletes, UUID;

    protected $dates = ['deleted_at'];


    protected $fillable = [
        'id',
        'amount',
        'token',
        'unit',
        'customer_id',
        'customer_name',
        'provider_name',
        'transaction_id',
        'user_id'
    ];

    

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'id',
        'transaction_id',
        'user_id',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transaction():BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }


}
