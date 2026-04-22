<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Upload Proyek - Konekin</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
        }
        .font-display {
            font-family: 'Space Grotesk', sans-serif;
        }
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body class="antialiased text-[#1E3A8A]">
    
    <!-- Navbar -->
    <x-dashboard-nav-umkm />

    <main class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
        
        <!-- Header -->
        <div class="mb-12">
            <a href="{{ route('dashboard.umkm') }}" class="inline-flex items-center gap-2 text-[#2563EB] font-bold text-sm hover:gap-3 transition-all mb-6">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l(7-7m-7 7h18" /></svg>
                Kembali ke Dashboard
            </a>
            <h1 class="font-display text-4xl font-bold text-[#1E3A8A] mb-4">Publikasikan Proyek Baru</h1>
            <p class="text-[#1E3A8A]/60 font-medium text-lg">Bagikan ide atau kebutuhan bisnismu dan biarkan talenta terbaik menemukannya.</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-[3rem] p-8 md:p-12 border border-[#2563EB]/5 shadow-2xl shadow-[#2563EB]/5">
            <form action="{{ route('projects.store') }}" method="POST" class="space-y-8">
                @csrf
                
                <!-- Project Title -->
                <div class="space-y-3">
                    <label for="title" class="text-sm font-extrabold text-[#1E3A8A] uppercase tracking-wider ml-1">Judul Proyek</label>
                    <input type="text" id="title" name="title" required placeholder="Contoh: Redesain Logo UMKM Kuliner" 
                           class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:border-[#2563EB] outline-none transition-all font-medium">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Category -->
                    <div class="space-y-3">
                        <label for="category" class="text-sm font-extrabold text-[#1E3A8A] uppercase tracking-wider ml-1">Kategori</label>
                        <select id="category" name="category" required 
                                class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:border-[#2563EB] outline-none transition-all font-medium appearance-none">
                            <option value="" disabled selected>Pilih Kategori</option>
                            <option value="Branding">Branding</option>
                            <option value="Social Media">Social Media</option>
                            <option value="Web Dev">Web Development</option>
                            <option value="Videography">Videography</option>
                            <option value="UI/UX Design">UI/UX Design</option>
                            <option value="Illustration">Illustration</option>
                        </select>
                    </div>

                    <!-- Budget -->
                    <div class="space-y-3">
                        <label for="budget" class="text-sm font-extrabold text-[#1E3A8A] uppercase tracking-wider ml-1">Estimasi Budget (Rp)</label>
                        <input type="text" id="budget" name="budget" required placeholder="Contoh: 2.500.000" 
                               class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:border-[#2563EB] outline-none transition-all font-medium">
                    </div>
                </div>

                <!-- Description -->
                <div class="space-y-3">
                    <label for="description" class="text-sm font-extrabold text-[#1E3A8A] uppercase tracking-wider ml-1">Deskripsi Proyek</label>
                    <textarea id="description" name="description" required rows="5" placeholder="Jelaskan secara detail mengenai proyek yang ingin Anda buat..." 
                              class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:border-[#2563EB] outline-none transition-all font-medium"></textarea>
                </div>

                <!-- Requirements -->
                <div class="space-y-3">
                    <label for="requirements" class="text-sm font-extrabold text-[#1E3A8A] uppercase tracking-wider ml-1">Kebutuhan / Spesifikasi (Opsional)</label>
                    <textarea id="requirements" name="requirements" rows="3" placeholder="Contoh: Harus berpengalaman di industri F&B, paham gaya minimalis, dll." 
                              class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:border-[#2563EB] outline-none transition-all font-medium"></textarea>
                </div>

                <!-- Tips / Notice -->
                <div class="p-6 bg-blue-50 rounded-3xl border border-blue-100/50 flex gap-4">
                    <div class="shrink-0 w-10 h-10 bg-white rounded-xl flex items-center justify-center text-[#2563EB] shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <p class="text-sm text-[#1E3A8A]/80 font-medium leading-relaxed">
                        <span class="font-bold text-[#2563EB]">Tips:</span> Berikan deskripsi yang jelas dan anggaran yang realistis untuk mendapatkan tawaran terbaik dari para profesional kreatif kami.
                    </p>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-5 bg-[#1E3A8A] text-white rounded-2xl font-bold text-lg hover:bg-[#2563EB] transition-all shadow-xl shadow-[#1E3A8A]/10 active:scale-[0.98]">
                    Publikasikan Proyek Sekarang
                </button>
            </form>
        </div>

    </main>

    <!-- Footer Simple -->
    <footer class="py-10 border-t border-[#2563EB]/10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
        <p class="text-[#1E3A8A]/40 text-sm font-bold">&copy; 2026 Konekin. Berdayakan UMKM Indonesia.</p>
        <div class="flex gap-8">
            <a href="#" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Bantuan</a>
            <a href="#" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Kebijakan</a>
        </div>
    </footer>

</body>
</html>
