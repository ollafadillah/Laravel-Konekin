<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Payment extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'payments';

    protected $fillable = [
        'project_id',
        'client_id',
        'client_name',
        'client_avatar',
        'amount',
        'currency',
        'payment_number',
        'description',
        'status', // pending, paid, failed, cancelled
        'payment_method', // transfer, card, e-wallet, etc
        'payment_date',
        'proof_file_url',
        'proof_file_type',
        'notes_from_umkm',
        'verified_at',
        'verified_by',
        'rejection_reason',
        'rejected_at',
        'created_at',
        'updated_at',
    ];

    protected $dates = [
        'payment_date',
        'verified_at',
        'rejected_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
    'payment_date' => 'datetime',
    'verified_at' => 'datetime',
    'rejected_at' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
];
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function generatePaymentNumber()
    {
        $prefix = 'PAY';
        $date = now()->format('Ymd');
        $random = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
        return "{$prefix}-{$date}-{$random}";
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }
}
