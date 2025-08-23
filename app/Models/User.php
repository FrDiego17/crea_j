<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'clerk_id',
        'web_password_set',
        'email_verified_at',
        'role',
        'id_admin',
        'card_number',
        'card_cvc',
        'card_expiry',
        'card_balance',
    ];


    protected $hidden = [
        'password',
        'remember_token',
        'card_number',
        'card_cvc',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'card_balance' => 'decimal:2',
        'web_password_set' => 'boolean',
    ];

    protected $attributes = [
        'role' => null,
        'id_admin' => null,
        'card_balance' => 0.00,
    ];


    public static function findByClerkId(string $clerkId)
    {
        return static::where('clerk_id', $clerkId)->first();
    }


    public function isFromClerk(): bool
    {
        return !empty($this->clerk_id);
    }


    public function isConductor(): bool
    {
        return $this->role === 'conductor';
    }

    public function isAdmin(): bool
    {
        return !empty($this->id_admin);
    }
}