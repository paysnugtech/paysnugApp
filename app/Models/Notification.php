<?php

namespace App\Models;

use App\Enums\EmailNotificationEnum;
use App\Enums\PushNotificationEnum;
use App\Enums\SmsNotificationEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    
    use HasFactory, HasUuids, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $dates = ['deleted_at'];

    protected $casts = [
        "is_email" => EmailNotificationEnum::class,
        "is_push" => PushNotificationEnum::class,
        "is_sms" => SmsNotificationEnum::class
    ];

    protected $hidden = [
        "id",
        "user_id",
        "deleted_at",
        "created_at",
        "updated_at"
    ];

    protected $fillable = [
        'id',
        'is_email',
        'is_push',
        'is_sms',
        'user_id'
    ];


    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
