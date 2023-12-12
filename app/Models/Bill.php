<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{
    
    use HasFactory, HasUuids, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $dates = ['deleted_at'];

    protected $casts = [

    ];

    protected $fillable = [
        'id',
        'number',
        'url',
        'is_verified',
        'remark',
        "user_id",
        "verification_id",
        "updated_by"
    ];

    protected $hidden = [
        'id',
        "number",
        "url",
        "remark",
        "user_id",
        "verification_id",
        "updated_by",
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    


    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function verification():BelongsTo
    {
        return $this->belongsTo(Verification::class);
    }
}
