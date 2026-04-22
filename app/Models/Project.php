<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Project extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'projects';

    protected $fillable = [
        'title',
        'description',
        'category',
        'budget',
        'deadline',
        'client_id',
        'client_name',
        'client_avatar',
        'status',
        'requirements',
        'thumbnail',
        'media_url',
        'media_type',
        'progress_percentage',
        'applications_count',
        'selected_creative_id',
        'selected_creative_name',
        'selected_creative_avatar',
        'escrow_status', // pending, held, released, refunded
        'escrow_transaction_id',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function escrowTransaction()
    {
        return $this->hasOne(EscrowTransaction::class, 'project_id');
    }
}
