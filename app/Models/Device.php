<?php

namespace App\Models;

use App\Enums\DevicePlatformEnum;
use App\Enums\DeviceTypeEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $dates = ['deleted_at'];


    protected $fillable = [
        'id',
        'device_name',
        'device_id',
        'device_type',
        'platform',
        'signature',
        'ip',
        'status',
        'user_id'
    ];
    
    protected $hidden = [
        'id',
        'signature',
        'user_id',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'device_type' => DeviceTypeEnum::class,
        'platform' => DevicePlatformEnum::class,
    ];


    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
