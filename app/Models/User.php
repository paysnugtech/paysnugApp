<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Device;
use App\Models\DeviceToken;
use App\Models\Level;
use App\Models\Log;
use App\Models\Service;
use App\Models\Verification;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, UUID;

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
        'finger_print',
        'pin',
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


    public function airtimeBeneficiary(string $id)
    {
        return $this->airtimeBeneficiaries()->where('id', $id)->first();
    }


    public function airtimeBeneficiaries(): HasMany
    {
        return $this->hasMany(AirtimeBeneficiary::class);
    }


    public function bankBeneficiary(string $id)
    {
        return $this->airtimeBeneficiaries()->where('id', $id)->first();
    }


    public function bankBeneficiaries(): HasMany
    {
        return $this->hasMany(BankBeneficiary::class);
    }


    public function cableBeneficiary(string $id)
    {
        return $this->cableBeneficiaries()->where('id', $id)->first();
    }


    public function cableBeneficiaries(): HasMany
    {
        return $this->hasMany(CableBeneficiary::class);
    }


    public function dataBeneficiary(string $id)
    {
        return $this->cableBeneficiaries()->where('id', $id)->first();
    }


    public function dataBeneficiaries(): HasMany
    {
        return $this->hasMany(DataBeneficiary::class);
    }


    public function device(): HasOne
    {
        return $this->hasOne(Device::class);
    }


    
    /* public function deviceToken(): HasOne
    {
        return $this->hasOne(DeviceToken::class, 'device_token_id');
    } */


    public function electricityBeneficiary(string $id)
    {
        return $this->electricityBeneficiaries()->where('id', $id)->first();
    }


    public function electricityBeneficiaries(): HasMany
    {
        return $this->hasMany(ElectricityBeneficiary::class);
    }


    public function level(): HasOne
    {
        return $this->hasOne(Level::class);
    }

    

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



    /* public function service(): HasOne
    {
        return $this->hasOne(Service::class);
    } */
    


    public function serviceById($id)
    {
        return Service::where('id', $id)->first();
    }
    


    public function serviceByName($name)
    {
        return $this->services->where('name', $name)->first();

    }


    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class)->withPivot('is_allow', 'is_free', 'free_count');
    }


    public function transaction($id)
    {
        return $this->transactions->where('id', $id)->first();
    }


    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }


    public function verification():HasOne
    {
        return $this->hasOne(Verification::class);
    }


    public function wallet($id)
    {
        return $this->wallets->where('id', $id)->first();
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
