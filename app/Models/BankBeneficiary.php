<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankBeneficiary extends Model
{
    
    
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'bank_beneficiaries';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $dates = ['deleted_at'];

    protected $casts = [

    ];

    protected $fillable = [
        'id',
        'account_name',
        'account_no',
        "bank_name",
        "bank_code",
        "user_id",
        "updated_by"
    ];

    protected $hidden = [
        'id',
        "user_id",
        "updated_by",
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    


    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
