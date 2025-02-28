<?php

namespace App\Models;

use App\Models\Scopes\AdminScope;
use App\Models\Scopes\MarketerScope;
use App\Traits\DefaultOrderTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Marketer extends User
{
    use HasFactory;
    use DefaultOrderTrait;

    protected $table = 'users';
    protected $guard_name = 'sanctum';

    protected static function booted(): void
    {
        static::addGlobalScope(new MarketerScope);
    }

    protected function model(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => Marketer::class,
        );
    }

    public function url(): string
    {
        return env('APP_FRONTEND_ADMIN_URL');
    }

}
