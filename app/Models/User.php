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
use App\Models\ProjectHistory;
use App\Support\MongoNotificationRoute;

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
        'creative_category',
        'skills',
        'onboarding_completed',
        'phone',
        'address',
        'city',
        'bio',
        'profile_photo',
        'google_id',
        'google_token',
        'latitude',
        'longitude',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'profile_border',
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
            'onboarding_completed' => 'boolean',
            'dismissed_notification_ids' => 'array',
            'last_ai_recommendation' => 'array',
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

    public function routeNotificationForDatabase($notification = null): MongoNotificationRoute
    {
        return new MongoNotificationRoute($this);
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

    public function ratingsQuery()
    {
        return Rating::where('to_user_id', (string) $this->getKey());
    }

    public function getAverageRatingAttribute()
    {
        $ratings = $this->ratingsQuery()->get(['rating']);

        if ($ratings->isEmpty()) {
            return 0;
        }

        $average = $ratings->avg(function ($rating) {
            return (float) ($rating->rating ?? 0);
        });

        return round((float) $average, 1);
    }

    public function getRatingsCountAttribute()
    {
        return $this->ratingsQuery()->count();
    }

    public function getFiveStarRatingsCountAttribute()
    {
        return $this->ratingsQuery()->where('rating', 5)->count();
    }

    public function getCreativeTierAttribute()
    {
        if (!$this->isCreativeWorker()) {
            return null;
        }

        $fiveStarCount = $this->five_star_ratings_count;

        if ($fiveStarCount >= 20) {
            return [
                'name' => 'Expert',
                'badge' => asset('images/assets/red%20star.png'),
                'color' => 'text-rose-600',
                'bg' => 'bg-rose-50',
            ];
        } elseif ($fiveStarCount >= 10) {
            return [
                'name' => 'Intermediate',
                'badge' => asset('images/assets/blue%20star.jpg'),
                'color' => 'text-blue-600',
                'bg' => 'bg-blue-50',
            ];
        } elseif ($fiveStarCount >= 1) {
            return [
                'name' => 'Beginner',
                'badge' => asset('images/assets/yellow%20stars.jpg'),
                'color' => 'text-amber-600',
                'bg' => 'bg-amber-50',
            ];
        }

        return null;
    }

    public function recentRatings(int $limit = 5)
    {
        return $this->ratingsQuery()
            ->with(['fromUser:id,name,profile_photo', 'project:id,title'])
            ->orderBy('created_at', 'desc')
            ->limit($limit);
    }

    public function getCompletedProjectsCountAttribute()
    {
        $activeCompleted = Project::where('selected_creative_id', $this->id)
            ->where('status', 'completed')
            ->count();

        $archivedCompleted = ProjectHistory::where('selected_creative_id', $this->id)
            ->where('history_type', 'completed')
            ->count();

        return $activeCompleted + $archivedCompleted;
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
