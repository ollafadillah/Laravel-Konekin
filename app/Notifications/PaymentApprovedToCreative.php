<?php

namespace App\Notifications;

use App\Models\Payment;
use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PaymentApprovedToCreative extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Payment $payment,
        public Project $project
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $amount = number_format($this->payment->amount, 0, ',', '.');

        return [
            'payment_id' => $this->payment->_id,
            'payment_number' => $this->payment->payment_number,
            'project_id' => $this->project?->id,
            'project_title' => $this->project?->title,
            'amount' => $this->payment->amount,
            'amount_formatted' => "Rp {$amount}",
            'currency' => $this->payment->currency,
            'verified_at' => $this->payment->verified_at,
            'message' => "Pembayaran untuk proyek '{$this->project?->title}' telah disetujui admin. Dana sudah masuk escrow. Anda bisa mulai mengerjakan proyek.",
            'type' => 'payment_approved_to_creative',
        ];
    }
}
