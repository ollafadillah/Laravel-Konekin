<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\EscrowTransaction;
use Illuminate\Http\Request;
use App\Notifications\EscrowPaymentReceived;
use App\Notifications\EscrowPaymentSuccessful;
use Illuminate\Support\Facades\Log;

class EscrowController extends Controller
{
    public function checkout($id)
    {
        $project = Project::findOrFail($id);

        if (auth()->id() !== (string) $project->client_id) {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        if ($project->status !== 'hired' || $project->escrow_status === 'held') {
            return redirect()->route('projects.show', $id)->with('error', 'Pembayaran sudah dilakukan atau proyek belum siap.');
        }

        $amount = (int) $project->budget;
        
        return view('projects.checkout', compact('project', 'amount'));
    }

    public function simulatePayment($id)
    {
        $project = Project::findOrFail($id);

        if (auth()->id() !== (string) $project->client_id) {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        $amount = (int) $project->budget;
        $platformFee = $amount * 0.10;
        $netAmount = $amount - $platformFee;
        $orderId = 'SIM-' . $project->id . '-' . time();

        // Create successful transaction record immediately (Simulated)
        $escrow = EscrowTransaction::create([
            'project_id' => $project->id,
            'payer_id' => $project->client_id,
            'payee_id' => $project->selected_creative_id,
            'amount' => $amount,
            'platform_fee' => $platformFee,
            'net_amount' => $netAmount,
            'status' => 'held',
            'midtrans_order_id' => $orderId,
            'midtrans_transaction_id' => 'SIM-TX-' . uniqid(),
            'midtrans_payment_type' => 'simulation',
        ]);

        $project->update([
            'escrow_status' => 'held',
            'status' => 'in_progress',
            'escrow_transaction_id' => $escrow->id
        ]);

        // Notify Creative Worker
        $escrow->payee->notify(new EscrowPaymentReceived($project, $escrow));

        // Notify UMKM
        $escrow->payer->notify(new EscrowPaymentSuccessful($project));

        return redirect()->route('projects.show', $project->id)->with('success', 'Pembayaran escrow berhasil disimulasikan! Proyek sekarang dalam pengerjaan.');
    }

    // This method is kept but simplified for the mock
    public function handleMidtransNotification(Request $request)
    {
        return response()->json(['success' => true, 'message' => 'Notification handled (Mock)']);
    }
}
