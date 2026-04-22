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
        'amount',
        'platform_fee',
        'net_amount',
        'status', // pending, held, released, refunded
        'midtrans_order_id',
        'midtrans_transaction_id',
        'midtrans_payment_type',
        'disbursement_id',
        'disbursement_status',
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
