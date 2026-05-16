<?php

namespace App\Notifications;

use App\Models\Payment;
use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PaymentRejectedToCreative extends Notification implements ShouldQueue
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
        return [
            'payment_id' => (string) $this->payment->getKey(),
            'payment_number' => $this->payment->payment_number,
            'project_id' => (string) $this->project->getKey(),
            'project_title' => $this->project->title,
            'rejection_reason' => $this->payment->rejection_reason,
            'rejected_at' => $this->payment->rejected_at,
            'message' => "Pembayaran untuk proyek '{$this->project->title}' ditolak admin. UMKM perlu mengirim ulang bukti pembayaran.",
            'type' => 'payment_rejected_to_creative',
        ];
    }
}
