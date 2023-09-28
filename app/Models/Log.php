<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Log extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $dates = ['deleted_at'];


    protected $fillable = [
        'id',
        'ip',
        'login_at',
        'logout_at',
        'status',
        'user_id'
    ];
    
    protected $hidden = [
        'id',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
    
    ];


    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
