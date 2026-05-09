<?php

namespace App\Http\Controllers;

use App\Models\EscrowTransaction;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CreativeEscrowController extends Controller
{
    /**
     * Get all escrow transactions for creative worker
     */
    public function index()
    {
        $user = auth()->user();

        if (!$user->isCreativeWorker()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Anda bukan Creative Worker.',
            ], 403);
        }

        $escrows = EscrowTransaction::where('payee_id', $user->id)
            ->with(['project', 'payer', 'payee'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Escrow transactions retrieved successfully',
            'data' => $escrows->map(fn ($escrow) => [
                'id' => (string) $escrow->id,
                'project_id' => (string) $escrow->project_id,
                'project_title' => $escrow->project->title ?? 'N/A',
                'payer' => [
                    'id' => (string) $escrow->payer->id,
                    'name' => $escrow->payer->name,
                ],
                'amount' => (int) $escrow->amount,
                'platform_fee' => (int) $escrow->platform_fee,
                'net_amount' => (int) $escrow->net_amount,
                'status' => $escrow->status,
                'disbursement_id' => $escrow->disbursement_id,
                'disbursement_status' => $escrow->disbursement_status,
                'created_at' => $escrow->created_at->toISOString(),
            ]),
        ], 200);
    }

    /**
     * Get single escrow transaction details
     */
    public function show($id)
    {
        $user = auth()->user();
        $escrow = EscrowTransaction::findOrFail($id);

        if ((string) $user->id !== (string) $escrow->payee_id) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => (string) $escrow->id,
                'project_id' => (string) $escrow->project_id,
                'project' => [
                    'id' => (string) $escrow->project->id,
                    'title' => $escrow->project->title,
                    'description' => $escrow->project->description,
                    'budget' => (int) $escrow->project->budget,
                    'status' => $escrow->project->status,
                ],
                'payer' => [
                    'id' => (string) $escrow->payer->id,
                    'name' => $escrow->payer->name,
                    'company' => $escrow->payer->company_name ?? null,
                ],
                'amount' => (int) $escrow->amount,
                'platform_fee' => (int) $escrow->platform_fee,
                'net_amount' => (int) $escrow->net_amount,
                'status' => $escrow->status,
                'midtrans_order_id' => $escrow->midtrans_order_id,
                'disbursement_id' => $escrow->disbursement_id,
                'disbursement_status' => $escrow->disbursement_status,
                'created_at' => $escrow->created_at->toISOString(),
            ],
        ], 200);
    }

    /**
     * Get earnings summary for creative worker
     */
    public function earnings()
    {
        $user = auth()->user();

        if (!$user->isCreativeWorker()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak.',
            ], 403);
        }

        $escrows = EscrowTransaction::where('payee_id', $user->id)->get();

        $totalEarned = $escrows->where('status', 'released')->sum('net_amount');
        $pendingRelease = $escrows->where('status', 'held')->sum('net_amount');
        $inDisbursement = $escrows->where('status', 'releasing')->sum('net_amount');

        return response()->json([
            'success' => true,
            'data' => [
                'total_earned' => (int) $totalEarned,
                'pending_release' => (int) $pendingRelease,
                'in_disbursement' => (int) $inDisbursement,
                'transactions_count' => $escrows->count(),
                'released_count' => $escrows->where('status', 'released')->count(),
            ],
        ], 200);
    }
}
