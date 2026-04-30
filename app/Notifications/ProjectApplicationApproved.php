<?php

namespace App\Notifications;

use App\Models\Project;
use App\Models\ProjectApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProjectApplicationApproved extends Notification
{
    use Queueable;

    protected Project $project;
    protected ProjectApplication $application;

    public function __construct(Project $project, ProjectApplication $application)
    {
        $this->project = $project;
        $this->application = $application;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Lamaran Disetujui - ' . $this->project->title)
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Selamat! UMKM telah menyetujui lamaranmu untuk proyek: ' . $this->project->title)
            ->line('Kamu sudah bisa mulai mengerjakan proyek ini dan memantau progress dari dashboard.')
            ->action('Lihat Progress Proyek', route('projects.progress.creative'))
            ->line('Tetap semangat berkarya bersama Konekin!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'project_application_approved',
            'title' => 'Lamaran Disetujui',
            'message' => 'UMKM telah menerima lamaranmu untuk proyek "' . $this->project->title . '".',
            'project_id' => (string) $this->project->id,
            'project_title' => $this->project->title,
            'application_id' => (string) $this->application->id,
            'action_url' => route('projects.progress.creative'),
        ];
    }
}
