<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Pembayaran (Simulasi) - Konekin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
        .font-display { font-family: 'Space Grotesk', sans-serif; }
    </style>
</head>
<body class="antialiased text-[#1E3A8A] min-h-screen flex items-center justify-center p-4">
    <div class="max-w-xl w-full bg-white rounded-[3rem] shadow-2xl overflow-hidden border border-[#2563EB]/5">
        <div class="p-10">
            <div class="flex flex-col items-center mb-10">
                <div class="w-20 h-20 bg-gradient-to-br from-[#2563EB] to-[#0A66C2] rounded-3xl flex items-center justify-center text-white text-3xl shadow-xl shadow-[#2563EB]/20 mb-6">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h1 class="font-display text-3xl font-bold text-center">Simulasi Pembayaran</h1>
                <p class="text-[#1E3A8A]/60 text-center mt-2">Mode Tugas Proyek: Pembayaran akan langsung dikonfirmasi.</p>
            </div>

            <div class="space-y-6">
                <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100">
                    <p class="text-[10px] font-black text-[#1E3A8A]/40 uppercase tracking-[0.2em] mb-2">Detail Proyek</p>
                    <h3 class="font-bold text-lg mb-1">{{ $project->title }}</h3>
                    <p class="text-sm text-[#1E3A8A]/60">Kreator: <span class="text-[#1E3A8A] font-bold">{{ $project->selected_creative_name }}</span></p>
                </div>

                <div class="flex justify-between items-center px-2">
                    <span class="text-slate-500 font-medium">Total Pembayaran</span>
                    <span class="text-2xl font-display font-bold text-[#2563EB]">Rp {{ number_format($amount, 0, ',', '.') }}</span>
                </div>

                <div class="pt-6">
                    <form action="{{ route('escrow.simulate', $project->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-[#2563EB] text-white py-5 rounded-2xl font-bold text-lg shadow-xl shadow-[#2563EB]/20 hover:bg-[#1E3A8A] transition-all transform hover:-translate-y-1">
                            Konfirmasi Pembayaran (Simulasi)
                        </button>
                    </form>
                    <p class="text-center text-[11px] text-slate-400 mt-4 font-medium italic">
                        *Ini adalah simulasi pembayaran untuk keperluan tugas proyek.
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-slate-50 p-6 text-center border-t border-slate-100">
            <a href="{{ route('projects.show', $project->id) }}" class="text-sm font-bold text-[#1E3A8A]/40 hover:text-[#2563EB] transition-colors">Batal & Kembali</a>
        </div>
    </div>
</body>
</html>
