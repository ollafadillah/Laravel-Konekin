<?php

namespace App\Notifications;

use App\Models\Project;
use App\Models\EscrowTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EscrowPaymentReceived extends Notification
{
    use Queueable;

    protected $project;
    protected $transaction;

    public function __construct(Project $project, EscrowTransaction $transaction)
    {
        $this->project = $project;
        $this->transaction = $transaction;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
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

    public function toArray(object $notifiable): array
    {
        return [
            'project_id' => $this->project->id,
            'title' => 'Pembayaran Escrow Diterima',
            'message' => 'Dana untuk proyek ' . $this->project->title . ' telah diamankan di escrow.',
        ];
    }
}
