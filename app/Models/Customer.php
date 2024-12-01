<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'surname',
        'middle_name',
        'dob',
        'sex',
        'account_id',
        'resident_state',
        'resident_lga',
        'resident_address',
        'occupation',
        'office_address',
        'state',
        'lga',
        'hometown',
        'phone',
        'next_of_kin',
        'relationship',
        'nok_phone',
        'acc_no',
        'branch',
        'group',
        'sb_card_no_from',
        'sb_card_no_to',
        'sb',
        'initial_unit',
        'user_id',
        'branch_id',
        'bank_name',
        'bank_code',
        'account_name',
        'account_number',
    ];

    protected  $with = ['customerWallet'];

    public function customerWallet():HasOne{
        return $this->hasOne(CustomerWallet::class);
    }

    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function branch():BelongsTo{
        return $this->belongsTo(Branch::class);
    }

}
