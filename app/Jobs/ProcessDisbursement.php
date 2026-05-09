<?php
namespace App\Jobs;

use App\Models\EscrowTransaction;
use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Notifications\EscrowFundsReleased;

class ProcessDisbursement implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $escrow;

    public function __construct(EscrowTransaction $escrow)
    {
        $this->escrow = $escrow;
    }

    public function handle()
    {
        try {
            // Reload escrow dengan relations yang dibutuhkan
            $escrow = EscrowTransaction::with(['project', 'payee', 'payer'])->findOrFail($this->escrow->id);
            $payee = $escrow->payee;
            $project = $escrow->project;

            // Simulation: No real API call to Midtrans Iris
            Log::info('Simulating disbursement for Escrow ID: ' . $escrow->id);

            // Mocking a short delay to simulate network call
            // usleep(500000); 

            $escrow->update([
                'status' => 'released',
                'disbursement_id' => 'SIM-DISB-' . uniqid(),
                'disbursement_status' => 'completed',
            ]);

            if ($project) {
                $project->update(['escrow_status' => 'released']);
            }
            
            // Notify Creative Worker jika ada
            if ($payee) {
                try {
                    $payee->notify(new EscrowFundsReleased($project, $escrow));
                } catch (\Exception $notifyError) {
                    Log::error('Notification Error (non-blocking): ' . $notifyError->getMessage());
                    // Jangan stop proses disbursement hanya karena notifikasi gagal
                }
            }

            Log::info('Disbursement simulated successfully for Escrow ID: ' . $escrow->id);
        } catch (\Exception $e) {
            Log::error('Disbursement Simulation Error: ' . $e->getMessage());
            if (isset($escrow)) {
                $escrow->update(['disbursement_status' => 'error']);
            }
        }
    }
}