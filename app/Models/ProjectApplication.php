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
        'proposal_original_name',
        'proposal_mime_type',
        'status',
        'applied_at',
        'approved_at',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function creative()
    {
        return $this->belongsTo(User::class, 'creative_id');
    }

    public function getProposalDisplayNameAttribute(): string
    {
        if (!empty($this->proposal_original_name)) {
            return $this->proposal_original_name;
        }

        $extension = $this->proposal_type ?: 'file';

        return 'proposal-' . (string) $this->id . '.' . $extension;
    }

    public function getProposalDownloadUrlAttribute(): ?string
    {
        if (empty($this->proposal_url)) {
            return null;
        }

        return $this->proposal_url;
    }
}
