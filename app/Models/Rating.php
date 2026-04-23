<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Rating extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'ratings';

    protected $fillable = [
        'project_id',
        'from_user_id', // UMKM
        'to_user_id',   // Creative Worker
        'rating',       // 1-5
        'comment',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}
