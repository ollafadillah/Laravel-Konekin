<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class EscrowTransaction extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'escrow_transactions';

    protected $fillable = [
        'project_id',
        'payer_id', // UMKM
        'payee_id', // Creative Worker
        'payment_id',
        'amount',
        'platform_fee',
        'net_amount',
        'status', // pending, held, releasing, released, refunded, disputed, rejected
        'midtrans_order_id',
        'midtrans_transaction_id',
        'midtrans_payment_type',
        'virtual_account_bank',
        'virtual_account_number',
        'payment_due_at',
        'proof_file_url',
        'proof_file_type',
        'payment_date',
        'verified_at',
        'verified_by',
        'rejection_reason',
        'rejected_at',
        'dispute_reason',
        'dispute_opened_at',
        'dispute_opened_by',
        'dispute_resolution',
        'dispute_resolved_at',
        'dispute_resolved_by',
        'admin_resolution_notes',
        'disbursement_id',
        'disbursement_status',
    ];

    protected $casts = [
        'payment_due_at' => 'datetime',
        'payment_date' => 'datetime',
        'verified_at' => 'datetime',
        'rejected_at' => 'datetime',
        'dispute_opened_at' => 'datetime',
        'dispute_resolved_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    public function payee()
    {
        return $this->belongsTo(User::class, 'payee_id');
    }
}
