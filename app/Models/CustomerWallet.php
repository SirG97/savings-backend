<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerWallet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'count',
        'balance',
        'loan'
    ];

    protected $casts = [
        'balance' => 'float',
    ];

    public function customer(): BelongsTo{
        return $this->belongsTo(Customer::class);
    }

}
