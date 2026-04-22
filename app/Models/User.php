<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use MongoDB\Laravel\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    protected $connection = 'mongodb';
    protected $collection = 'users';

    protected $fillable = [
        'name',
        'email', 
        'password',
        'type',
        'phone',
        'address',
        'city',
        'bio',
        'profile_photo',
        'status', // active, warned, suspended
        'warnings', // array of warning messages
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'warnings' => 'array',
        ];
    }

    public function isCreativeWorker(): bool
    {
        return $this->type === 'creative_worker';
    }

    public function isUMKM(): bool
    {
        return $this->type === 'umkm';
    }

    public function isAdmin(): bool
    {
        return $this->type === 'admin';
    }
}