<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'branch_id',
        'payment_method',
        'reference',
        'type',
        'transaction_type',
        'amount',
        'balance_before',
        'balance_after',
        'description',
        'remark',
        'date'
    ];

    protected $casts = [
        'amount' => 'float',
        'balance_before' => 'float',
        'balance_after' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

}
