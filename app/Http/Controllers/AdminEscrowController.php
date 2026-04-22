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

        $transactions = EscrowTransaction::with(['project', 'payer', 'payee'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.escrow.index', compact('transactions'));
    }

    public function release($id)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $escrow = EscrowTransaction::findOrFail($id);

        if ($escrow->status !== 'held') {
            return redirect()->back()->with('error', 'Transaksi tidak dalam status tertahan (held).');
        }

        // Check if project is completed (usually should be)
        $project = Project::findOrFail($escrow->project_id);
        if ($project->status !== 'completed' && $project->progress_percentage < 100) {
             // We might allow admin to force release, but usually it should be completed
             Log::warning('Admin releasing escrow for incomplete project: ' . $project->id);
        }

        try {
            // Update status to releasing/pending disbursement
            $escrow->update(['status' => 'releasing']);
            $project->update(['escrow_status' => 'releasing']);

            // Dispatch Disbursement Job
            ProcessDisbursement::dispatch($escrow);

            return redirect()->back()->with('success', 'Pencairan dana sedang diproses.');
        } catch (\Exception $e) {
            Log::error('Escrow Release Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memproses pencairan dana.');
        }
    }
}
