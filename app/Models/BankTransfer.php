<?php

namespace App\Models;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankTransfer extends Model
{
    
    
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'bank_transfers';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $dates = ['deleted_at'];

    protected $casts = [

    ];

    protected $fillable = [
        'id',
        'amount',
        'account_name',
        'account_no',
        "bank_name",
        "bank_code",
        "narration",
        "session_id",
        "transaction_id",
        "user_id",
        "updated_by"
    ];

    protected $hidden = [
        'id',
        "transaction_id",
        "user_id",
        "updated_by",
        'deleted_at',
        'created_at',
        'updated_at',
    ];



    public function transaction():BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }


    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
