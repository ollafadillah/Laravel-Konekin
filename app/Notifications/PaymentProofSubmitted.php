<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentProofSubmitted extends Notification implements ShouldQueue
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
            ->subject("Bukti Pembayaran Diterima - {$this->payment->payment_number}")
            ->greeting("Halo Admin,")
            ->line("UMKM telah mengirimkan bukti pembayaran!")
            ->line("**No. Invoice:** {$this->payment->payment_number}")
            ->line("**Klien:** {$this->payment->client_name}")
            ->line("**Jumlah:** Rp " . number_format($this->payment->amount, 0, ',', '.'))
            ->line("**Metode:** " . ucfirst(str_replace('-', ' ', $this->payment->payment_method ?? 'N/A')))
            ->line("**Tanggal Pembayaran:** {$this->payment->payment_date}")
            ->action('Verifikasi Pembayaran', route('payments.show', $this->payment->_id))
            ->line('')
            ->line("Silakan review dan verifikasi pembayaran dalam dashboard admin.")
            ->salutation('Sistem Konekin');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'payment_id' => $this->payment->_id,
            'payment_number' => $this->payment->payment_number,
            'client_name' => $this->payment->client_name,
            'amount' => $this->payment->amount,
            'type' => 'payment_proof_submitted',
        ];
    }
}
