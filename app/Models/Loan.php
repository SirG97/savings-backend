<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'min_amount',
        'max_amount',
        'interest_rate',
        'late_payment_interest_rate',
        'min_duration',
        'max_duration',
        'description',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
        'min_amount' => 'float',
        'max_amount' => 'float',
        'min_duration' => 'float',
        'max_duration' => 'float',
        'interest_rate' => 'float',
        'late_payment_interest_rate' => 'float',
    ];

}
