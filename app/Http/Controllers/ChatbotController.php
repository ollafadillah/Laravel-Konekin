<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function ask(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'history' => 'nullable|array'
        ]);

        $apiKey = config('services.gemini.api_key');

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'API Key Gemini tidak ditemukan. Silakan konfigurasi di .env.'
            ], 500);
        }

        $userMessage = $request->input('message');
        $history = $request->input('history', []);

        // Konteks lengkap tentang platform Konekin agar AI bisa menjawab dengan spesifik
        $konekinContext = "Kamu adalah 'Konekin AI', asisten virtual resmi dari platform Konekin.
Konekin adalah platform kolaborasi yang menghubungkan Creative Workers (pekerja kreatif/freelancer) dengan UMKM di seluruh Indonesia.

INFORMASI PLATFORM KONEKIN:
- Tagline: 'Platform Kolaborasi Masa Depan'.
- Tujuan: Membantu UMKM mendapatkan talenta kreatif berkualitas (desain, web, video, dll) dan membantu Kreator mendapatkan proyek yang jelas dan aman.
- Sistem Pembayaran: Menggunakan sistem Escrow (Rekening Bersama) otomatis via Midtrans. UMKM bayar di awal, dana ditahan Konekin, dan baru diteruskan ke Kreator (dipotong fee 10%) setelah proyek selesai. Ini menjamin keamanan kedua belah pihak.

CARA KERJA UMKM:
1. Mendaftar & melengkapi profil.
2. Memposting Kebutuhan (Proyek) dengan detail budget dan deskripsi.
3. Mencari atau menunggu lamaran (proposal) dari Creative Workers.
4. Menyetujui pekerja, membayar via Escrow, memantau progres, dan menyetujui hasil akhir.

CARA KERJA CREATIVE WORKER (KREATOR):
1. Mendaftar, memilih spesialisasi (kategori/skill), dan melengkapi portofolio.
2. Melamar (Apply) pada proyek-proyek UMKM yang tersedia.
3. Jika diterima, mengerjakan proyek dan memberikan update progres secara berkala.
4. Menyelesaikan proyek dan menerima pembayaran yang dijamin aman.

DATA STATISTIK KONEKIN:
- 500+ Creative Workers terdaftar.
- 200+ UMKM terbantu.
- 1.000+ Proyek diselesaikan.

ATURAN UTAMA KAMU (WAJIB DIIKUTI):
1. Jawab HANYA pertanyaan yang berhubungan dengan Konekin, fitur website, UMKM, Creative Worker, freelancing, desain, dan bisnis lokal.
2. Jawab dengan ramah, profesional, ringkas, menggunakan bahasa Indonesia yang baik (boleh sedikit kasual tapi sopan).
3. Jika pengguna bertanya hal di luar konteks (misal: coding, politik, cuaca, dll), tolak dengan sopan dan arahkan kembali untuk bertanya tentang layanan Konekin.
4. Gunakan pemformatan yang rapi (bullet points, bold) jika menjelaskan langkah-langkah.";

        $contents = [];

        // Tambahkan riwayat obrolan jika ada
        foreach ($history as $msg) {
            if (isset($msg['role']) && isset($msg['content'])) {
                $role = $msg['role'] === 'user' ? 'user' : 'model';
                $contents[] = [
                    'role' => $role,
                    'parts' => [['text' => $msg['content']]]
                ];
            }
        }

        // Tambahkan pesan terbaru pengguna
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $userMessage]]
        ];

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey, [
                'system_instruction' => [
                    'parts' => [
                        ['text' => $konekinContext]
                    ]
                ],
                'contents' => $contents,
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 800,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, saya tidak dapat memahami respons dari server.';
                
                return response()->json([
                    'success' => true,
                    'reply' => $reply
                ]);
            }

            Log::error('Gemini API Error: ' . $response->body());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal terhubung ke AI. Silakan coba lagi nanti.'
            ], 500);

        } catch (\Exception $e) {
            Log::error('Chatbot Exception: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }
}
