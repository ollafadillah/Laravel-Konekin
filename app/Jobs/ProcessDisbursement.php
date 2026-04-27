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
        $escrow = $this->escrow;
        $payee = $escrow->payee; // Creative Worker

        // Simulation: No real API call to Midtrans Iris
        Log::info('Simulating disbursement for Escrow ID: ' . $escrow->id);

        try {
            // Mocking a short delay to simulate network call
            // usleep(500000); 

            $escrow->update([
                'status' => 'released',
                'disbursement_id' => 'SIM-DISB-' . uniqid(),
                'disbursement_status' => 'completed',
            ]);

            Project::where('_id', $escrow->project_id)->update(['escrow_status' => 'released']);
            
            // Notify Creative Worker
            $payee->notify(new EscrowFundsReleased($escrow->project, $escrow));

            Log::info('Disbursement simulated successfully for Escrow ID: ' . $escrow->id);
        } catch (\Exception $e) {
            Log::error('Disbursement Simulation Error: ' . $e->getMessage());
            $escrow->update(['disbursement_status' => 'error']);
        }
    }
}