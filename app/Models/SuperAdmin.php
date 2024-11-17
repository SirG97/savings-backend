<?php

namespace App\Models;

use App\Models\Scopes\SuperAdminScope;
use App\Traits\DefaultOrderTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SuperAdmin extends User
{
    use HasFactory;
    use DefaultOrderTrait;
    protected $table = 'users';
    protected $guard_name = 'sanctum';

    protected static function booted(): void
    {
        static::addGlobalScope(new SuperAdminScope);
    }

    protected function model(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => SuperAdmin::class,
        );
    }

    public function url(): string
    {
        return env('APP_FRONTEND_ADMIN_URL');
    }

}
