<?php

namespace App\Http\Controllers;

use App\Models\EscrowTransaction;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Jobs\ProcessDisbursement;
use Illuminate\Support\Facades\Log;

class AdminEscrowController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        // Semua transaksi escrow
        $transactions = EscrowTransaction::with(['project', 'payer', 'payee'])
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingApprovalEscrows = EscrowTransaction::where('status', 'held')
            ->with(['project', 'payer', 'payee'])
            ->orderBy('updated_at', 'desc')
            ->get()
            ->filter(fn ($escrow) => ($escrow->project->status ?? null) === 'pending_admin_approval');

        $disputeEscrows = EscrowTransaction::where('status', 'disputed')
            ->with(['project', 'payer', 'payee'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $pendingApprovalProjects = $pendingApprovalEscrows->map(function ($escrow) {
            $payer = $escrow->payer;
            $payee = $escrow->payee;
            $project = $escrow->project;
            
            return (object) [
                'id' => (string) $escrow->project_id,
                'title' => $project->title ?? 'N/A',
                'client_name' => $payer ? $payer->name : 'N/A',
                'selected_creative_name' => $payee ? $payee->name : 'N/A',
                'progress_percentage' => (int) ($project->progress_percentage ?? 0),
                'escrow' => $escrow,
            ];
        });

        $pendingApprovalCount = $pendingApprovalProjects->count();
        $totalPending = $pendingApprovalProjects->sum(fn ($p) => (int) $p->escrow->net_amount);
        $disputeCount = $disputeEscrows->count();

        return view('admin.escrow.index', compact(
            'transactions',
            'pendingApprovalProjects',
            'pendingApprovalCount',
            'totalPending',
            'disputeEscrows',
            'disputeCount'
        ));
    }

    public function release($id)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        try {
            $escrow = EscrowTransaction::find($id);
            
            if (!$escrow) {
                return redirect()->back()->with('error', 'Transaksi escrow tidak ditemukan dengan ID: ' . $id);
            }

            if ($escrow->status !== 'held') {
                return redirect()->back()->with('error', 'Transaksi tidak dalam status tertahan (held). Status saat ini: ' . $escrow->status);
            }

            // Ensure project exists before updating
            $project = Project::find($escrow->project_id);
            if (!$project) {
                return redirect()->back()->with('error', 'Proyek terkait tidak ditemukan.');
            }

            // Update status to releasing/pending disbursement
            $escrow->update(['status' => 'releasing']);
            $project->update(['escrow_status' => 'releasing']);

            // Dispatch Disbursement Job (sync untuk langsung dijalankan)
            ProcessDisbursement::dispatchSync($escrow);

            Log::info('Admin approved escrow release', [
                'escrow_id' => $escrow->id,
                'project_id' => $project->id,
                'admin_id' => auth()->user()->id,
            ]);

            return redirect()->route('admin.escrow.index')->with('success', 'Pencairan dana sedang diproses.');
            
        } catch (\Exception $e) {
            Log::error('Escrow Release Error', [
                'escrow_id' => $id ?? 'unknown',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'Gagal memproses pencairan: ' . $e->getMessage());
        }
    }
}
