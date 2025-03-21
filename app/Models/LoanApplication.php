<?php

namespace App\Models;

use App\Traits\DefaultOrderTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanApplication extends Model
{
    use HasFactory, SoftDeletes, DefaultOrderTrait;

    protected $fillable = [
        'loan_id',
        'user_id',
        'branch_id',
        'customer_id',
        'amount',
        'status',
        'interest_amount',
        'total_amount',
        'duration',
        'due_date',
        'disbursed_at',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejection_reason',
        'late_payment_interest',
        'total_payable_amount',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    protected $with = [
        'user', 'customer', 'branch'
    ];

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
