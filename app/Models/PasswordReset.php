<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    protected $table = 'password_reset_tokens';
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
