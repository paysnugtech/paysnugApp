<?php

namespace App\Models;

use App\Enums\ServiceTypeEnum;
use App\Enums\TransactionTypeEnum;
use App\Enums\TransactionStatusEnum;
use App\Models\BankTransfer;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Transaction extends Model
{
    
    
    use HasFactory, Notifiable, SoftDeletes, HasUuids;

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
        'number',
        'amount',
        'balance_before',
        'balance_after',
        'commission',
        'discount',
        'profit',
        'type',
        'service_type',
        'narration',
        'status',
        'remark',
        'reference_no',
        'user_id',
        'updated_by',
        'order_by'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'type' => TransactionTypeEnum::class,
        'service_type' => ServiceTypeEnum::class,
        'status' => TransactionStatusEnum::class,
    ];

    

    public function airtime(): HasOne
    {
        return $this->hasOne(Airtime::class);
    }

    

    public function cable(): HasOne
    {
        return $this->hasOne(Cable::class);
    }

    

    public function data(): HasOne
    {
        return $this->hasOne(Data::class);
    }

    

    public function electricity(): HasOne
    {
        return $this->hasOne(Electricity::class);
    }

    

    public function transfer(): HasOne
    {
        return $this->hasOne(BankTransfer::class);
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}
