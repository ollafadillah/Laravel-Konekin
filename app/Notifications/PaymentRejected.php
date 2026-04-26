<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentRejected extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Payment $payment)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Pembayaran Ditolak - {$this->payment->payment_number}")
            ->greeting("Halo {$this->payment->client_name},")
            ->line("Pembayaran Anda telah ditolak oleh admin.")
            ->line("**No. Invoice:** {$this->payment->payment_number}")
            ->line("**Jumlah:** Rp " . number_format($this->payment->amount, 0, ',', '.'))
            ->line('')
            ->line("**Alasan Penolakan:**")
            ->line($this->payment->rejection_reason ?? 'Tidak ada alasan yang diberikan')
            ->line('')
            ->line("Silakan review alasan penolakan dan upload bukti yang benar.")
            ->action('Upload Ulang Bukti', route('payments.show', $this->payment->_id))
            ->line('')
            ->line("Jika ada pertanyaan, hubungi admin kami.")
            ->salutation('Tim Konekin');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'payment_id' => $this->payment->_id,
            'payment_number' => $this->payment->payment_number,
            'rejection_reason' => $this->payment->rejection_reason,
            'rejected_at' => $this->payment->rejected_at,
            'type' => 'payment_rejected',
        ];
    }
}
