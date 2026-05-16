<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentInvoiceCreated extends Notification implements ShouldQueue
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
            ->subject("Invoice Pembayaran Proyek Anda - {$this->payment->payment_number}")
            ->greeting("Halo {$this->payment->client_name},")
            ->line("Invoice pembayaran untuk proyek Anda telah dibuat!")
            ->line("**No. Invoice:** {$this->payment->payment_number}")
            ->line("**Jumlah:** Rp " . number_format($this->payment->amount, 0, ',', '.'))
            ->line("**Proyek:** {$this->payment->description}")
            ->action('Lihat Detail Pembayaran', route('payments.show', $this->payment->_id))
            ->line('')
            ->line("Silakan upload bukti pembayaran dalam waktu maksimal 7 hari.")
            ->line("Jika ada pertanyaan, hubungi admin kami.")
            ->salutation('Terima kasih,');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'payment_id' => (string) $this->payment->getKey(),
            'payment_number' => $this->payment->payment_number,
            'amount' => $this->payment->amount,
            'project_id' => (string) $this->payment->project_id,
            'project_title' => $this->payment->description,
            'message' => "Invoice {$this->payment->payment_number} sudah dibuat. Silakan transfer dan upload bukti pembayaran.",
            'type' => 'payment_invoice_created',
        ];
    }
}
