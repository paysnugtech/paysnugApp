<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    
    use HasFactory, HasUuids, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'user_id',
        'country',
        'street',
        'city',
        'postal_code'
    ];


    public $hidden = [
        'user_id',
        'deleted_at',
        'created_at',
        'updated_at',
    ];


    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
