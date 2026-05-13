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
        'escrow_status', // unpaid, pending, held, released, refunded, disputed
        'escrow_transaction_id',
        'payment_id',
        'completion_approved_at',
        'completion_approved_by',
        'dispute_opened_at',
        'dispute_opened_by',
        'dispute_reason',
        'dispute_resolution',
        'dispute_resolved_at',
        'dispute_resolved_by',
        'admin_resolution_notes',
        'revision_requested_at',
        'revision_requested_by',
        'revision_reason',
        'rejection_reason',
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
