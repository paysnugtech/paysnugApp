<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisterToken extends Model
{
    use HasFactory;

    protected $table = 'register_verification_tokens';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'email';

    protected $fillable = [
        'email',
        'token',
        'expire_in',
        'created_at',
    ];
}
