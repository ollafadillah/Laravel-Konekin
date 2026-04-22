<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EscrowPaymentSuccessful extends Notification
{
    use Queueable;

    protected $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pembayaran Escrow Berhasil - ' . $this->project->title)
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Terima kasih! Pembayaran escrow untuk proyek "' . $this->project->title . '" telah kami terima.')
            ->line('Dana Anda sekarang aman di escrow dan akan ditahan sampai Anda menyetujui hasil kerja kreator.')
            ->line('Kreator telah diberitahu untuk segera memulai pekerjaan.')
            ->action('Pantau Progress', route('projects.show', $this->project->id))
            ->line('Terima kasih telah mempercayai Konekin!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'project_id' => $this->project->id,
            'title' => 'Pembayaran Berhasil',
            'message' => 'Pembayaran escrow untuk ' . $this->project->title . ' telah berhasil.',
        ];
    }
}
