<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ProjectProgressUpdate extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'project_progress_updates';

    protected $fillable = [
        'project_id',
        'creative_id',
        'creative_name',
        'note',
        'progress_percentage',
        'media_url',
        'media_type',
    ];
}
