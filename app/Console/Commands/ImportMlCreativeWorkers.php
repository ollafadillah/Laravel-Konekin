<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ImportMlCreativeWorkers extends Command
{
    /**
     * Nama perintah yang akan dijalankan di terminal.
     */
    protected $signature = 'app:import-ml-cw';

    /**
     * Deskripsi perintah.
     */
    protected $description = 'Impor seluruh 5.000 Creative Workers dari dataset ML ke database MongoDB';

    public function handle()
    {
        $path = base_path('ml-service/data/cw_dataset.csv');
        
        if (!file_exists($path)) {
            $this->error("File dataset tidak ditemukan di: $path");
            return 1;
        }

        $file = fopen($path, 'r');
        $header = fgetcsv($file); // Melewati baris pertama (header)

        if (!$header) {
            $this->error("Gagal membaca header CSV.");
            fclose($file);
            return 1;
        }

        $this->info("Memulai proses impor data kreator dari dataset...");
        
        $count = 0;
        $batch = [];
        $batchSize = 500;
        
        // Pre-hash password agar tidak dihitung ulang 5.000 kali (sangat lambat)
        $defaultPassword = Hash::make('password123');
        
        // Ambil semua email yang sudah ada untuk menghindari duplikat tanpa query satu-satu
        $existingEmails = User::where('type', 'creative_worker')->pluck('email')->toArray();
        $existingEmails = array_flip($existingEmails); // Untuk lookup O(1)

        $bar = $this->output->createProgressBar();
        $bar->start();

        while (($row = fgetcsv($file)) !== false) {
            if (count($row) < 22) continue;

            $email = $row[2];
            
            // Skip jika email sudah ada di DB
            if (isset($existingEmails[$email])) {
                $bar->advance();
                continue;
            }

            $batch[] = [
                'name' => $row[1],
                'email' => $email,
                'password' => $defaultPassword,
                'type' => 'creative_worker',
                'creative_category' => $row[5],
                'skills' => array_values(array_filter(array_map('trim', explode(',', $row[7])))),
                'city' => $row[10],
                'bio' => $row[6],
                'onboarding_completed' => true,
                'profile_verified' => ((int)$row[21] === 1),
                'status' => ((int)$row[20] === 1 ? 'active' : 'inactive'),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($batch) >= $batchSize) {
                User::insert($batch);
                $count += count($batch);
                $batch = [];
                gc_collect_cycles();
            }

            $bar->advance();
        }

        // Insert sisa batch
        if (count($batch) > 0) {
            User::insert($batch);
            $count += count($batch);
        }

        fclose($file);
        $bar->finish();
        
        $this->newLine();
        $this->info("Berhasil mengimpor $count data Creative Workers ke database MongoDB.");
        
        return 0;
    }
}
