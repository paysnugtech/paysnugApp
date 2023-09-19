<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CardType extends Model
{
    
    use HasFactory, HasUuids, SoftDeletes;

    
    protected $dates = ['deleted_at'];
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'card_types';

    protected $casts = [

    ];

    protected $fillable = [
        'id',
        'name',
        'description',
        'status',
        "created_by",
        "updated_by",
    ];

    protected $hidden = [
        'id',
        'status',
        "created_by",
        "updated_by",
        'deleted_at',
        'created_at',
        'updated_at',
    ];


    public function cards():HasMany
    {
        return $this->hasMany(Card::class);
    }
}
