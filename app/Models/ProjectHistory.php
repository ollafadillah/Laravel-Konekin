<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ProjectHistory extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'project_histories';

    protected $fillable = [
        'original_project_id',
        'client_id',
        'client_name',
        'client_avatar',
        'title',
        'description',
        'category',
        'budget',
        'deadline',
        'requirements',
        'thumbnail',
        'media_url',
        'media_type',
        'selected_creative_id',
        'selected_creative_name',
        'selected_creative_avatar',
        'progress_percentage',
        'applications_count',
        'status',
        'history_type',
        'archive_reason',
        'rating',
        'comment',
        'rated_by',
        'rated_at',
        'archived_at',
        'source_created_at',
        'source_updated_at',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
            'rated_at' => 'datetime',
            'archived_at' => 'datetime',
            'source_created_at' => 'datetime',
            'source_updated_at' => 'datetime',
            'rating' => 'integer',
            'progress_percentage' => 'integer',
            'applications_count' => 'integer',
        ];
    }
}
