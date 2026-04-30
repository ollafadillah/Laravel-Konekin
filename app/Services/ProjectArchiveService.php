<?php

namespace App\Services;

use App\Models\Project;
use App\Models\ProjectApplication;
use App\Models\ProjectHistory;
use App\Models\ProjectProgressUpdate;
use App\Models\Rating;

class ProjectArchiveService
{
    public function syncCompletedProjectsForClient(string $clientId): int
    {
        $archivedCount = 0;

        Project::where('client_id', $clientId)
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->get()
            ->each(function (Project $project) use (&$archivedCount) {
                $existingHistory = ProjectHistory::where('original_project_id', (string) $project->id)->first();
                if ($existingHistory) {
                    $project->delete();
                    return;
                }

                $rating = Rating::where('project_id', $project->id)->first();
                if (!$rating) {
                    return;
                }

                $this->archiveCompletedProject($project, $rating);
                $archivedCount++;
            });

        return $archivedCount;
    }

    public function archiveCompletedProject(Project $project, Rating $rating): ProjectHistory
    {
        $history = ProjectHistory::create([
            'original_project_id' => (string) $project->id,
            'client_id' => $project->client_id,
            'client_name' => $project->client_name,
            'client_avatar' => $project->client_avatar,
            'title' => $project->title,
            'description' => $project->description,
            'category' => $project->category,
            'budget' => $project->budget,
            'deadline' => $project->deadline,
            'requirements' => $project->requirements,
            'thumbnail' => $project->thumbnail,
            'media_url' => $project->media_url,
            'media_type' => $project->media_type,
            'selected_creative_id' => $project->selected_creative_id,
            'selected_creative_name' => $project->selected_creative_name,
            'selected_creative_avatar' => $project->selected_creative_avatar,
            'progress_percentage' => (int) ($project->progress_percentage ?? 0),
            'applications_count' => (int) ($project->applications_count ?? 0),
            'status' => 'completed',
            'history_type' => 'completed',
            'archive_reason' => 'completed_and_rated',
            'rating' => (int) $rating->rating,
            'comment' => $rating->comment,
            'rated_by' => $rating->from_user_id,
            'rated_at' => $rating->created_at ?? now(),
            'archived_at' => now(),
            'source_created_at' => $project->created_at,
            'source_updated_at' => $project->updated_at,
        ]);

        $this->deleteProjectDependencies($project);
        $project->delete();

        return $history;
    }

    public function deleteUnfinishedProject(Project $project): void
    {
        $this->deleteProjectDependencies($project);
        $project->delete();
    }

    private function deleteProjectDependencies(Project $project): void
    {
        ProjectProgressUpdate::where('project_id', $project->id)->delete();
        ProjectApplication::where('project_id', $project->id)->delete();
    }
}
