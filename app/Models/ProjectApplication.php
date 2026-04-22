<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ProjectApplication extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'project_applications';

    protected $fillable = [
        'project_id',
        'creative_id',
        'creative_name',
        'creative_avatar',
        'creative_city',
        'message',
        'proposal_url',
        'proposal_type',
        'status',
        'applied_at',
    ];
}
