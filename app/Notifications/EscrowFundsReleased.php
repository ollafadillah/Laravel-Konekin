<?php

namespace App\Notifications;

use App\Models\Project;
use App\Models\EscrowTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EscrowFundsReleased extends Notification
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
            ->subject('Dana Proyek Telah Dicairkan - ' . $this->project->title)
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Kabar baik! Dana untuk proyek "' . $this->project->title . '" telah dicairkan ke rekening Anda.')
            ->line('Nominal bersih yang dikirim: Rp ' . number_format($this->transaction->net_amount, 0, ',', '.'))
            ->line('Potongan platform (10%): Rp ' . number_format($this->transaction->platform_fee, 0, ',', '.'))
            ->line('Dana akan sampai di rekening Anda sesuai dengan waktu proses bank.')
            ->action('Lihat Detail Proyek', route('projects.show', $this->project->id))
            ->line('Terima kasih atas kerja keras Anda!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'project_id' => $this->project->id,
            'title' => 'Dana Escrow Dicairkan',
            'message' => 'Dana untuk proyek ' . $this->project->title . ' telah dikirim ke rekening Anda.',
        ];
    }
}
