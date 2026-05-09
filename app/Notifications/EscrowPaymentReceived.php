<?php

namespace App\Notifications;

use App\Models\Project;
use App\Models\EscrowTransaction;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EscrowPaymentReceived extends Notification
{
    protected $project;
    protected $transaction;

    public function __construct(Project $project, EscrowTransaction $transaction)
    {
        $this->project = $project;
        $this->transaction = $transaction;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pembayaran Escrow Diterima - ' . $this->project->title)
            ->greeting('Halo, ' . $notifiable->name)
            ->line('UMKM telah melakukan pembayaran escrow untuk proyek: ' . $this->project->title)
            ->line('Dana sebesar Rp ' . number_format($this->transaction->amount, 0, ',', '.') . ' kini ditahan dengan aman di platform kami.')
            ->line('Anda dapat mulai mengerjakan proyek ini sekarang.')
            ->action('Lihat Proyek', route('projects.show', $this->project->id))
            ->line('Terima kasih telah menggunakan Konekin!');
    }
}
