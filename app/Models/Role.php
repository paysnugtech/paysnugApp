<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'id',
        'name',
        'description',
    ];


    protected $hidden = [
        'id',
        'created_by',
        'updated_by',
        'deleted_at',
        'created_at',
        'updated_at'
    ];


    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
