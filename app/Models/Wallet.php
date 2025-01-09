<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'branch_id',
        'bank',
        'cash',
        'balance'
    ];

    protected $casts = [
        'bank' => 'float',
        'cash' => 'float',
        'balance' => 'float',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

}
