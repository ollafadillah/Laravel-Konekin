<?php

namespace App\Http\Controllers;

use App\Models\EscrowTransaction;
use App\Models\Project;
use App\Jobs\ProcessDisbursement;
use App\Helpers\CurrencyHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProjectApprovalController extends Controller
{
    /**
     * UMKM approve project completion dan trigger escrow release ke admin
     */
    public function approveCompletion($id)
    {
        $user = auth()->user();

        if (!$user->isUMKM()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya UMKM yang dapat approve proyek.',
            ], 403);
        }

        try {
            $project = Project::where('client_id', $user->id)->findOrFail($id);

            // Validasi bahwa proyek sudah 100% selesai
            if ((int) ($project->progress_percentage ?? 0) < 100) {
                return response()->json([
                    'success' => false,
                    'message' => 'Proyek belum 100% selesai. Progress saat ini: ' . (int) ($project->progress_percentage ?? 0) . '%',
                ], 400);
            }

            // Validasi ada escrow transaction
            $escrow = EscrowTransaction::where('project_id', $project->id)
                ->where('status', 'held')
                ->orderBy('updated_at', 'desc')
                ->first();
            if (!$escrow) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada transaksi escrow untuk proyek ini. Pembayaran belum dilakukan?',
                ], 400);
            }

            // Validasi escrow status harus 'held'
            if ($escrow->status !== 'held') {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi escrow tidak dalam status tertahan (held). Status: ' . $escrow->status,
                ], 400);
            }

            $project->update([
                'status' => 'pending_admin_approval',
                'completion_approved_at' => now(),
                'completion_approved_by' => $user->id,
            ]);

            // Log approval
            Log::info('Project completion approved by UMKM', [
                'project_id' => $project->id,
                'budget' => (int) CurrencyHelper::extract($project->budget),
                'selected_creative_id' => $project->selected_creative_id,
            ]);

            if (!request()->expectsJson()) {
                return redirect()->route('projects.progress')
                    ->with('success', 'Proyek sudah disetujui selesai. Dana tetap ditahan platform sampai admin mencairkan ke creative worker.');
            }

            return response()->json([
                'success' => true,
                'message' => 'Proyek telah Anda setujui untuk selesai. Tim admin akan meninjau dan mencairkan dana ke kreator.',
                'data' => [
                    'project_id' => (string) $project->id,
                    'status' => $project->status,
                    'escrow_id' => (string) $escrow->id,
                    'escrow_status' => $escrow->status,
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Project Approval Error: ' . $e->getMessage());
            if (!request()->expectsJson()) {
                return redirect()->back()->with('error', 'Gagal approve proyek: ' . $e->getMessage());
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal approve proyek: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function openDispute($id, Request $request)
    {
        $user = auth()->user();

        if (!$user->isUMKM()) {
            return redirect()->back()->with('error', 'Hanya UMKM yang dapat mengajukan dispute.');
        }

        $validated = $request->validate([
            'reason' => 'required|string|min:20|max:1000',
        ], [
            'reason.required' => 'Alasan dispute wajib diisi.',
            'reason.min' => 'Alasan dispute minimal 20 karakter agar admin punya konteks yang cukup.',
        ]);

        try {
            $project = Project::where('client_id', $user->id)->findOrFail($id);
            $escrow = EscrowTransaction::where('project_id', $project->id)
                ->where('status', 'held')
                ->orderBy('updated_at', 'desc')
                ->first();

            if (!$escrow || $escrow->status !== 'held') {
                return redirect()->back()->with('error', 'Dispute hanya bisa diajukan setelah dana masuk dan ditahan di escrow.');
            }

            $escrow->update([
                'status' => 'disputed',
                'dispute_reason' => $validated['reason'],
                'dispute_opened_at' => now(),
                'dispute_opened_by' => $user->id,
                'disbursement_status' => 'disputed',
            ]);

            $project->update([
                'status' => 'disputed',
                'escrow_status' => 'disputed',
                'dispute_reason' => $validated['reason'],
                'dispute_opened_at' => now(),
                'dispute_opened_by' => $user->id,
            ]);

            Log::info('Project dispute opened by UMKM', [
                'project_id' => $project->id,
                'escrow_id' => $escrow->id,
                'umkm_id' => $user->id,
            ]);

            return redirect()->route('projects.progress')
                ->with('success', 'Dispute berhasil diajukan. Dana tetap ditahan sampai admin/mediator mengambil keputusan.');
        } catch (\Exception $e) {
            Log::error('Open Dispute Error: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal mengajukan dispute: ' . $e->getMessage());
        }
    }

    public function requestRevision($id, Request $request)
    {
        $user = auth()->user();

        if (!$user->isUMKM()) {
            return redirect()->back()->with('error', 'Hanya UMKM yang dapat meminta revisi.');
        }

        $validated = $request->validate([
            'reason' => 'required|string|min:10|max:1000',
        ], [
            'reason.required' => 'Catatan revisi wajib diisi.',
            'reason.min' => 'Catatan revisi minimal 10 karakter.',
        ]);

        try {
            $project = Project::where('client_id', $user->id)->findOrFail($id);
            $escrow = EscrowTransaction::where('project_id', $project->id)
                ->where('status', 'held')
                ->orderBy('updated_at', 'desc')
                ->first();

            if (!$escrow) {
                return redirect()->back()->with('error', 'Revisi setelah draft 100% hanya bisa diminta setelah dana masuk escrow.');
            }

            $project->update([
                'status' => 'revision',
                'revision_requested_at' => now(),
                'revision_requested_by' => $user->id,
                'revision_reason' => $validated['reason'],
            ]);

            Log::info('Project revision requested by UMKM', [
                'project_id' => $project->id,
                'escrow_id' => $escrow->id,
                'umkm_id' => $user->id,
            ]);

            return redirect()->route('projects.progress')
                ->with('success', 'Permintaan revisi dikirim. Dana tetap ditahan platform sampai hasil akhir disetujui.');
        } catch (\Exception $e) {
            Log::error('Request Revision Error: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal meminta revisi: ' . $e->getMessage());
        }
    }

    public function resolveDispute($id, Request $request)
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            return redirect()->back()->with('error', 'Akses ditolak. Hanya admin yang dapat menyelesaikan dispute.');
        }

        $validated = $request->validate([
            'resolution' => 'required|in:release,refund',
            'admin_resolution_notes' => 'required|string|min:10|max:1000',
        ]);

        try {
            $escrow = EscrowTransaction::with('project')->findOrFail($id);
            $project = $escrow->project;

            if ($escrow->status !== 'disputed') {
                return redirect()->back()->with('error', 'Escrow ini tidak sedang dalam status dispute.');
            }

            $resolutionData = [
                'dispute_resolution' => $validated['resolution'],
                'dispute_resolved_at' => now(),
                'dispute_resolved_by' => $user->id,
                'admin_resolution_notes' => $validated['admin_resolution_notes'],
            ];

            if ($validated['resolution'] === 'release') {
                $escrow->update(array_merge($resolutionData, [
                    'status' => 'releasing',
                    'disbursement_status' => 'approved_after_dispute',
                ]));

                if ($project) {
                    $project->update(array_merge($resolutionData, [
                        'status' => 'completed',
                        'escrow_status' => 'releasing',
                    ]));
                }

                ProcessDisbursement::dispatch($escrow);

                return redirect()->back()->with('success', 'Dispute diselesaikan: dana disetujui untuk dicairkan ke creative worker.');
            }

            $escrow->update(array_merge($resolutionData, [
                'status' => 'refunded',
                'disbursement_status' => 'refunded_to_umkm',
            ]));

            if ($project) {
                $project->update(array_merge($resolutionData, [
                    'status' => 'payment_refunded',
                    'escrow_status' => 'refunded',
                ]));
            }

            return redirect()->back()->with('success', 'Dispute diselesaikan: dana ditandai untuk dikembalikan ke UMKM.');
        } catch (\Exception $e) {
            Log::error('Resolve Dispute Error: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal menyelesaikan dispute: ' . $e->getMessage());
        }
    }

    /**
     * Admin review dan approve final project completion (WEB ROUTE)
     */
    public function adminApproveCompletion($id, Request $request)
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            return redirect()->back()->with('error', 'Akses ditolak. Hanya admin yang dapat approve.');
        }

        try {
            $project = Project::findOrFail($id);

            // Check if project is completed or has held escrow
            $escrow = EscrowTransaction::where('project_id', $project->id)
                ->where('status', 'held')
                ->orderBy('updated_at', 'desc')
                ->first();
            if (!$escrow || $escrow->status !== 'held') {
                return redirect()->back()->with('error', 'Escrow transaction tidak valid atau tidak dalam status held.');
            }

            if ((int) ($project->progress_percentage ?? 0) < 100) {
                return redirect()->back()->with('error', 'Proyek belum 100% selesai.');
            }

            // Update project status to completed
            $project->update([
                'status' => 'completed'
            ]);

            // Update escrow status to releasing
            $escrow->update(['status' => 'releasing']);

            // Dispatch disbursement job
            ProcessDisbursement::dispatch($escrow);

            Log::info('Project completion approved by admin - disbursement processing', [
                'project_id' => $project->id,
                'admin_id' => $user->id,
                'escrow_id' => $escrow->id,
            ]);

            return redirect()->back()->with('success', 'Proyek diterima. Dana sedang diproses untuk pencairan ke kreator.');

        } catch (\Exception $e) {
            Log::error('Admin Project Approval Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal approve proyek: ' . $e->getMessage());
        }
    }

    /**
     * Admin reject project completion (WEB ROUTE)
     */
    public function adminRejectCompletion($id, Request $request)
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            return redirect()->back()->with('error', 'Akses ditolak. Hanya admin yang dapat reject.');
        }

        try {
            $validated = $request->validate([
                'reason' => 'required|string|max:500',
            ]);

            $project = Project::findOrFail($id);

            // Just reset project status back to in_progress (it should already be completed from UMKM approval)
            $project->update([
                'status' => 'in_progress',
                'rejection_reason' => $validated['reason'],
            ]);

            Log::info('Project completion rejected by admin', [
                'project_id' => $project->id,
                'admin_id' => $user->id,
                'reason' => $validated['reason'],
            ]);

            return redirect()->back()->with('success', 'Proyek dikembalikan untuk perbaikan. Kreator akan menerima notifikasi.');

        } catch (\Exception $e) {
            Log::error('Admin Project Rejection Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal reject proyek: ' . $e->getMessage());
        }
    }

    /**
     * Admin approve project via API
     */
    public function apiAdminApproveCompletion($id)
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya admin yang dapat approve.',
            ], 403);
        }

        try {
            $project = Project::findOrFail($id);

            $escrow = EscrowTransaction::where('project_id', $project->id)
                ->where('status', 'held')
                ->orderBy('updated_at', 'desc')
                ->first();
            if (!$escrow || $escrow->status !== 'held') {
                return response()->json([
                    'success' => false,
                    'message' => 'Escrow transaction tidak valid atau tidak dalam status held.',
                ], 400);
            }

            if ((int) ($project->progress_percentage ?? 0) < 100) {
                return response()->json([
                    'success' => false,
                    'message' => 'Proyek belum 100% selesai.',
                ], 400);
            }

            // Update project status to completed
            $project->update([
                'status' => 'completed'
            ]);

            // Update escrow status to releasing
            $escrow->update(['status' => 'releasing']);

            // Dispatch disbursement job
            ProcessDisbursement::dispatch($escrow);

            Log::info('Project completion approved by admin via API', [
                'project_id' => $project->id,
                'admin_id' => $user->id,
                'escrow_id' => $escrow->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Proyek diterima. Dana sedang diproses untuk pencairan ke kreator.',
                'data' => [
                    'project_id' => (string) $project->id,
                    'status' => $project->status,
                    'escrow_status' => $escrow->status,
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Admin Project Approval Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal approve proyek: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Admin reject project via API
     */
    public function apiAdminRejectCompletion($id, Request $request)
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya admin yang dapat reject.',
            ], 403);
        }

        try {
            $validated = $request->validate([
                'reason' => 'required|string|max:500',
            ]);

            $project = Project::findOrFail($id);

            $project->update([
                'status' => 'in_progress',
                'rejection_reason' => $validated['reason'],
            ]);

            Log::info('Project completion rejected by admin via API', [
                'project_id' => $project->id,
                'admin_id' => $user->id,
                'reason' => $validated['reason'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Proyek dikembalikan untuk perbaikan. Kreator akan menerima notifikasi.',
            ], 200);

        } catch (\Exception $e) {
            Log::error('Admin Project Rejection Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal reject proyek: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get projects pending admin approval
     */
    public function getPendingApproval()
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya admin yang dapat melihat ini.',
            ], 403);
        }

        $projects = Project::where('status', 'pending_admin_approval')
            ->with(['client', 'escrowTransaction'])
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(fn ($project) => [
                'id' => (string) $project->id,
                'title' => $project->title,
                'client_name' => $project->client_name,
                'progress_percentage' => (int) ($project->progress_percentage ?? 0),
                'budget' => (int) CurrencyHelper::extract($project->budget),
                'escrow' => [
                    'id' => (string) $project->escrowTransaction->id,
                    'status' => $project->escrowTransaction->status,
                    'amount' => (int) $project->escrowTransaction->amount,
                    'net_amount' => (int) $project->escrowTransaction->net_amount,
                    'platform_fee' => (int) $project->escrowTransaction->platform_fee,
                ],
                'updated_at' => $project->updated_at->toISOString(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Proyek pending approval',
            'count' => $projects->count(),
            'data' => $projects,
        ], 200);
    }
}
