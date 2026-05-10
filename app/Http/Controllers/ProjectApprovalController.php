<?php

namespace App\Http\Controllers;

use App\Models\EscrowTransaction;
use App\Models\Project;
use App\Jobs\ProcessDisbursement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Notifications\ProjectCompletedForAdmin;

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
            $escrow = EscrowTransaction::where('project_id', $project->id)->first();
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

            // Update project status ke pending_admin_approval
            $project->update([
                'status' => 'pending_admin_approval'
            ]);

            // Log approval
            Log::info('Project completion approved by UMKM', [
                'project_id' => $project->id,
                'budget' => (int) CurrencyHelper::extract($project->budget),
                'selected_creative_id' => $project->selected_creative_id,
            ]);

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
            return response()->json([
                'success' => false,
                'message' => 'Gagal approve proyek: ' . $e->getMessage(),
            ], 500);
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
            $escrow = EscrowTransaction::where('project_id', $project->id)->first();
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

            $escrow = EscrowTransaction::where('project_id', $project->id)->first();
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
