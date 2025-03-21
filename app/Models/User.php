<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laragear\TwoFactor\TwoFactorAuthentication;
use Laragear\TwoFactor\Contracts\TwoFactorAuthenticatable;
use Laravel\Scout\Searchable;

class User extends Authenticatable implements TwoFactorAuthenticatable
{
    use HasApiTokens, Searchable, HasFactory, Notifiable, SoftDeletes, TwoFactorAuthentication;
    protected $table = 'users';
    protected $guard_name = 'sanctum';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'middle_name',
        'email',
        'password',
        'pin',
        'phone',
        'default_pin',
        'model',
        'two_factor',
        'socialite_signup',
        'form_signup',
        'active',
        'type',
        'default_password',
        'deactivated_at',
        'suspended_at',
        'email_verified_at',
        'kyc_verified_at',
        'phone_verified_at',
        'two_factor',
        'branch_id'
    ];

    public function toSearchableArray()
    {
        return [
            'id' => (int)$this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
        ];
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor',
    ];

    protected  $with = ['branch'];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function customer():hasMany{
        return $this->hasMany(Customer::class);
    }

    public function branch():belongsTo{
        return $this->belongsTo(Branch::class);
    }
}
