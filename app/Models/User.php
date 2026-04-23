<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
// Hapus: use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;  // Tambahkan ini
use MongoDB\Laravel\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject  // implements JWTSubject
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;  // Hapus HasApiTokens

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

    /**
     * Get the identifier that will be stored in the JWT.
     * Required method for JWTSubject interface.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return custom claims to be added to the JWT.
     * Required method for JWTSubject interface.
     */
    public function getJWTCustomClaims()
    {
        return [
            'type' => $this->type,
            'name' => $this->name,
        ];
    }
}