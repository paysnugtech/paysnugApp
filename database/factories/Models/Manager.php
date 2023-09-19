<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manager extends Model
{
    
    use HasFactory, HasUuids, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'first_name',
        'other_name',
        'phone_no',
        'email',
        'whatsapp_no'
    ];


    public function users():HasMany
    {
        return $this->hasMany(User::class);
    }


    protected $hidden = [
        "status",
        "deleted_at",
        "created_at",
        "updated_at"
    ];
}
