<?php

namespace App\Models;

use App\Models\User;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class DataBeneficiary extends Model
{
    use HasFactory, HasUuids, Notifiable, SoftDeletes;

    
    public $incrementing = false;
    protected $keyType = 'string';
    protected $dates = ['deleted_at'];



    protected $fillable = [
        'id',
        'customer_id',
        'provider_name',
        'user_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
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
}
