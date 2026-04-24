<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
// Hapus: use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;  // Tambahkan ini
use MongoDB\Laravel\Auth\User as Authenticatable;
use App\Models\Rating;
use App\Models\Project;

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
        'google_id',
        'google_token',
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
    public function isAdmin(): bool
    {
        return $this->type === 'admin';
    }

    public function escrowPayments()
    {
        return $this->hasMany(EscrowTransaction::class, 'payer_id');
    }

    public function escrowEarnings()
    {
        return $this->hasMany(EscrowTransaction::class, 'payee_id');
    }

    public function ratingsReceived()
    {
        return $this->hasMany(Rating::class, 'to_user_id');
    }

    public function getAverageRatingAttribute()
    {
        $ratings = $this->ratingsReceived();
        if ($ratings->count() === 0) {
            return 0;
        }
        return round($ratings->avg('rating'), 1);
    }

    public function getCompletedProjectsCountAttribute()
    {
        return Project::where('selected_creative_id', $this->id)
            ->where('status', 'completed')
            ->count();
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