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
            $escrow = EscrowTransaction::with(['project', 'payee', 'payer'])->find($this->escrow->id);
            
            if (!$escrow) {
                Log::error('ProcessDisbursement: Escrow not found - ' . $this->escrow->id);
                return;
            }
            
            $payee = $escrow->payee;
            $project = $escrow->project;

            // Simulation: No real API call to Midtrans Iris
            Log::info('Simulating disbursement for Escrow ID: ' . $escrow->id);

            // Update escrow status
            $escrow->update([
                'status' => 'released',
                'disbursement_id' => 'SIM-DISB-' . uniqid(),
                'disbursement_status' => 'completed',
            ]);

            // Update project status
            if ($project) {
                $updateData = [
                    'escrow_status' => 'released',
                    'status' => 'completed',  // Mark project as completed so UMKM can rate
                ];
                
                $project->update($updateData);
                
                Log::info('Project updated to completed', [
                    'project_id' => $project->id,
                    'new_status' => 'completed',
                ]);
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
            Log::error('Disbursement Simulation Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'escrow_id' => $this->escrow->id ?? null,
            ]);
            if (isset($escrow)) {
                $escrow->update(['disbursement_status' => 'error']);
            }
        }
    }
}