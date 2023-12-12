<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Profile extends Model
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
        'first_name',
        'other_name',
        'phone_no',
        'gender',
        'dob',
        'picture_url',
        'user_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'id',
        'user_id',
        'deleted_at',
        'created_at',
        'updated_at'
    ];


    public function address(): HasOne
    {
        return $this->hasOne(Address::class);
    }


    public function fullName()
    {
        return $this->first_name ." ". $this->other_name;
    }


    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
