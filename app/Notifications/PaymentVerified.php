<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentVerified extends Notification implements ShouldQueue
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
            ->subject("Pembayaran Terverifikasi ✓ - {$this->payment->payment_number}")
            ->greeting("Selamat {$this->payment->client_name}!")
            ->line("Pembayaran Anda telah berhasil diverifikasi oleh admin.")
            ->line("**No. Invoice:** {$this->payment->payment_number}")
            ->line("**Jumlah:** Rp " . number_format($this->payment->amount, 0, ',', '.'))
            ->line("**Status:** ✓ Terverifikasi")
            ->action('Lihat Detail', route('payments.show', $this->payment->_id))
            ->line('')
            ->line("Terima kasih telah menggunakan layanan Konekin!")
            ->line("Proyek Anda sekarang final dan pembayaran berhasil diproses.")
            ->salutation('Tim Konekin');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'payment_id' => $this->payment->_id,
            'payment_number' => $this->payment->payment_number,
            'amount' => $this->payment->amount,
            'verified_at' => $this->payment->verified_at,
            'type' => 'payment_verified',
        ];
    }
}
