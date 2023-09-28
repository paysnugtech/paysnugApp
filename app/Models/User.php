<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Device;
use App\Models\DeviceToken;
use App\Models\Log;
use App\Models\Service;
use App\Models\Verification;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'email',
        'password',
        'level',
        'finger_print',
        'pin',
        'role_id',
        'manager_id',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }


    public function device(): HasOne
    {
        return $this->hasOne(Device::class);
    }


    /* public function deviceToken(): HasOne
    {
        return $this->hasOne(DeviceToken::class, 'device_token_id');
    } */


    public function log(): HasOne
    {
        return $this->hasOne(Log::class);
    }


    public function manager():BelongsTo
    {
        return $this->belongsTo(Manager::class);
    }


    public function notification(): HasOne
    {
        return $this->hasOne(Notification::class);
    }


    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }


    public function role():BelongsTo
    {
        return $this->belongsTo(Role::class);
    }


    public function service(): HasOne
    {
        return $this->hasOne(Service::class);
    }


    public function verification():HasOne
    {
        return $this->hasOne(Verification::class);
    }


    public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
