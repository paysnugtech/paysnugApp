<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceToken extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'device_verification_tokens';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'token',
        'email',
        'expire_in',
        'created_at',
    ];
}
