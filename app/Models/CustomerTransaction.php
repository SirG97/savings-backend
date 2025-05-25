<?php

namespace App\Models;

use App\Models\Scopes\ExcludeReversedTransactionsScope;
use App\Traits\DefaultOrderTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class CustomerTransaction extends Model
{
    use HasFactory, Searchable, SoftDeletes, DefaultOrderTrait;

    protected $fillable = [
        'user_id',
        'branch_id',
        'customer_id',
        'payment_method',
        'reference',
        'type',
        'transaction_type',
        'amount',
        'balance_before',
        'balance_after',
        'description',
        'remark',
        'date',
        'reversed_by',
        'reverses_id'
    ];

    protected  $with = ['customer','branch','user'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function toSearchableArray()
    {
        return [
            'id' => (int)$this->id,
            'amount' => $this->amount,

        ];
    }

    protected static function booted()
    {
        static::addGlobalScope(new ExcludeReversedTransactionsScope);
    }

    // To query reversed transactions when needed
    public function scopeWithReversed($query)
    {
        return $query->withoutGlobalScope(ExcludeReversedTransactionsScope::class);
    }

    // Relationship to the transaction this reverses
    public function reversedTransaction()
    {
        return $this->belongsTo(CustomerTransaction::class, 'reverses_id');
    }

    // Relationship to the reversal transaction (if this was reversed)
    public function reversalTransaction()
    {
        return $this->hasOne(CustomerTransaction::class, 'reversed_by');
    }

}
