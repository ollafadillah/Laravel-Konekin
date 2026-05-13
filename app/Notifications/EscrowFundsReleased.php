<?php

namespace App\Notifications;

use App\Models\Project;
use App\Models\EscrowTransaction;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EscrowFundsReleased extends Notification
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
            ->subject('Dana Proyek Telah Dicairkan - ' . $this->project->title)
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Kabar baik! Dana untuk proyek "' . $this->project->title . '" telah dicairkan ke rekening Anda.')
            ->line('Nominal bersih yang dikirim: Rp ' . number_format($this->transaction->net_amount, 0, ',', '.'))
            ->line('Potongan platform (15%): Rp ' . number_format($this->transaction->platform_fee, 0, ',', '.'))
            ->line('Dana akan sampai di rekening Anda sesuai dengan waktu proses bank.')
            ->action('Lihat Detail Proyek', route('projects.show', $this->project->id))
            ->line('Terima kasih atas kerja keras Anda!');
    }
}
