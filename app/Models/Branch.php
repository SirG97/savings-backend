<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'active'
    ];

    protected $with = ['wallet'];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function wallet():HasOne{
        return $this->hasOne(Wallet::class);
    }

    public function customers():HasMany{
        return $this->hasMany(Customer::class);
    }

    public function user():HasMany{
        return $this->hasMany(User::class);
    }

}
