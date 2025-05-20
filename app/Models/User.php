<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'card_number',
        'card_cvc',
        'card_expiry',
        'card_balance',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function booted()
{
    static::created(function ($user) {
        $user->update([
            'card_number' => self::generateCardNumber(),
            'card_cvc' => rand(100, 999),
            'card_expiry' => now()->addYear()->format('m/y'),
            'card_balance' => 0
        ]);
    });
}

protected static function generateCardNumber()
{
    return implode(' ', array_map(fn() => rand(1000, 9999), range(1, 4)));
}
}

