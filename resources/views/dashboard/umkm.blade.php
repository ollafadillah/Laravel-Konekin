<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard UMKM - Konekin</title>
    
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
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body class="antialiased text-[#1E3A8A]">
    
    <!-- Navbar -->
    <x-dashboard-nav-umkm />

    <!-- Main Content -->
    <main class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        
        <!-- Welcome Header -->
        <div class="mb-12">
            <h1 class="font-display text-3xl md:text-4xl font-bold text-[#1E3A8A] mb-2">Halo, {{ $user->name }}! 🏢</h1>
            <p class="text-[#1E3A8A]/60 font-medium text-lg">Selamat datang di dashboard UMKM. Temukan talenta kreatif terbaik untuk bisnismu.</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <!-- Stat 1 -->
            <div class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-[#EFF6FF] rounded-2xl group-hover:bg-[#2563EB] group-hover:text-white transition-colors text-[#2563EB]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                    </div>
                    <span class="text-blue-500 text-xs font-bold bg-blue-50 px-2.5 py-1 rounded-full">+1 Baru</span>
                </div>
                <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Proyek Berjalan</h3>
                <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">{{ $projectsInProgress }}</p>
            </div>

            <!-- Stat 2 -->
            <div class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-[#FFF7ED] rounded-2xl group-hover:bg-[#EA580C] group-hover:text-white transition-colors text-[#EA580C]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>
                <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Total Proyek</h3>
                <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">{{ $projectsCount }}</p>
            </div>

            <!-- Stat 3 -->
            <div class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-[#F0FDF4] rounded-2xl group-hover:bg-[#16A34A] group-hover:text-white transition-colors text-[#16A34A]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                </div>
                <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Apply Masuk</h3>
                <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">{{ $totalApplications }}</p>
            </div>

            <!-- Stat 4 -->
            <div class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-[#FAF5FF] rounded-2xl group-hover:bg-[#9333EA] group-hover:text-white transition-colors text-[#9333EA]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                    </div>
                </div>
                <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Status Akun</h3>
                <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">{{ $projectsCount > 0 ? 'Aktif' : '0' }}</p>
            </div>
        </div>

        <!-- Content Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Recommended Creators (Left 2/3) -->
            <div class="lg:col-span-2 space-y-6">
                <div class="flex justify-between items-center">
                    <h2 class="font-display text-2xl font-bold text-[#1E3A8A]">Kreator Rekomendasi</h2>
                    <a href="{{ route('kreator.index') }}" class="text-[#2563EB] text-sm font-bold hover:underline">Lihat Semua</a>
                </div>

                <!-- Creator Card 1 -->
                <div class="bg-white p-6 rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all flex flex-col md:flex-row gap-6 items-center">
                    <div class="w-full md:w-40 h-40 rounded-[2rem] overflow-hidden shrink-0">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=1974&auto=format&fit=crop" alt="Creator" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-grow">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-3 py-1 bg-[#EFF6FF] text-[#2563EB] text-[10px] font-extrabold uppercase tracking-widest rounded-full">Graphic Designer</span>
                            <span class="text-[#1E3A8A]/40 text-xs font-bold">Aktif 5 menit yang lalu</span>
                        </div>
                        <h4 class="text-xl font-display font-bold text-[#1E3A8A] mb-2">Alex Rivera</h4>
                        <p class="text-[#1E3A8A]/60 text-sm line-clamp-2 mb-4 font-medium">Spesialis branding dan desain UI/UX dengan pengalaman lebih dari 5 tahun membantu UMKM berkembang.</p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="flex text-yellow-400">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <span class="ml-1 text-xs font-bold text-[#1E3A8A]">4.9 (124 Review)</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="text-[#1E3A8A] font-display font-bold text-sm">Mulai dari Rp 500rb</span>
                                <button class="px-5 py-2.5 bg-[#1E3A8A] text-white text-xs font-bold rounded-xl hover:bg-[#2563EB] transition-colors">Undang</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Creator Card 2 -->
                <div class="bg-white p-6 rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all flex flex-col md:flex-row gap-6 items-center">
                    <div class="w-full md:w-40 h-40 rounded-[2rem] overflow-hidden shrink-0">
                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?q=80&w=1974&auto=format&fit=crop" alt="Creator" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-grow">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-3 py-1 bg-[#FDF2F8] text-[#DB2777] text-[10px] font-extrabold uppercase tracking-widest rounded-full">Content Creator</span>
                            <span class="text-[#1E3A8A]/40 text-xs font-bold">Aktif 1 jam yang lalu</span>
                        </div>
                        <h4 class="text-xl font-display font-bold text-[#1E3A8A] mb-2">Sarah Wijaya</h4>
                        <p class="text-[#1E3A8A]/60 text-sm line-clamp-2 mb-4 font-medium">Membantu bisnis kuliner dan fashion untuk go-viral lewat konten video kreatif dan estetik di TikTok/Instagram.</p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="flex text-yellow-400">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <span class="ml-1 text-xs font-bold text-[#1E3A8A]">5.0 (86 Review)</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="text-[#1E3A8A] font-display font-bold text-sm">Mulai dari Rp 750rb</span>
                                <button class="px-5 py-2.5 bg-[#1E3A8A] text-white text-xs font-bold rounded-xl hover:bg-[#2563EB] transition-colors">Undang</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Messages & Quick Actions (Right 1/3) -->
            <div class="space-y-8">
                <div>
                    <h2 class="font-display text-2xl font-bold text-[#1E3A8A] mb-6">Pesan Terbaru</h2>
                    <div class="bg-white rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm divide-y divide-[#2563EB]/5">
                        <!-- Message 1 -->
                        <div class="p-5 flex gap-4 hover:bg-[#F8FAFC] transition-colors cursor-pointer">
                            <img src="https://ui-avatars.com/api/?name=Alex&background=random" class="w-12 h-12 rounded-2xl shadow-sm">
                            <div class="flex-grow overflow-hidden">
                                <div class="flex justify-between items-center mb-1">
                                    <h5 class="text-sm font-bold text-[#1E3A8A]">Alex Rivera</h5>
                                    <span class="text-[10px] text-[#1E3A8A]/40 font-bold">10:45</span>
                                </div>
                                <p class="text-xs text-[#1E3A8A]/60 line-clamp-1 font-medium">Saya sudah mengirimkan draf pertama logonya...</p>
                            </div>
                        </div>
                        <!-- Message 2 -->
                        <div class="p-5 flex gap-4 hover:bg-[#F8FAFC] transition-colors cursor-pointer">
                            <img src="https://ui-avatars.com/api/?name=Sarah&background=random" class="w-12 h-12 rounded-2xl shadow-sm">
                            <div class="flex-grow overflow-hidden">
                                <div class="flex justify-between items-center mb-1">
                                    <h5 class="text-sm font-bold text-[#1E3A8A]">Sarah Wijaya</h5>
                                    <span class="text-[10px] text-[#1E3A8A]/40 font-bold">Kemarin</span>
                                </div>
                                <p class="text-xs text-[#1E3A8A]/60 line-clamp-1 font-medium">Kapan kita bisa meeting untuk bahas konsepnya?</p>
                            </div>
                        </div>
                        <div class="p-4 text-center">
                            <a href="#" class="text-xs font-bold text-[#2563EB] hover:underline">Semua Pesan</a>
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="font-display text-2xl font-bold text-[#1E3A8A] mb-6">Aksi Cepat</h2>
                    <div class="grid grid-cols-1 gap-4">
                        <a href="{{ route('projects.create') }}" class="flex items-center gap-4 bg-[#1E3A8A] p-5 rounded-3xl text-white hover:bg-[#2563EB] transition-all shadow-xl shadow-[#1E3A8A]/10 group">
                            <div class="p-3 bg-white/10 rounded-2xl group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm">Upload Proyek Baru</h4>
                                <p class="text-xs text-white/60">Mulai cari talenta hari ini</p>
                            </div>
                        </a>
                        <a href="{{ route('profile.index') }}" class="flex items-center gap-4 bg-white p-5 rounded-3xl border border-[#2563EB]/5 hover:border-[#2563EB]/20 transition-all shadow-sm group">
                            <div class="p-3 bg-[#EFF6FF] text-[#2563EB] rounded-2xl group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-[#1E3A8A]">Lengkapi Profil Usaha</h4>
                                <p class="text-xs text-[#1E3A8A]/60">Tingkatkan kepercayaan kreator</p>
                            </div>
                        </a>
                        <a href="{{ route('projects.progress') }}" class="flex items-center gap-4 bg-white p-5 rounded-3xl border border-[#2563EB]/5 hover:border-[#2563EB]/20 transition-all shadow-sm group">
                            <div class="p-3 bg-[#EFF6FF] text-[#2563EB] rounded-2xl group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6m4 6V7m4 10v-3M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-[#1E3A8A]">Progress Proyek</h4>
                                <p class="text-xs text-[#1E3A8A]/60">Pantau apply dan update progress</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer Simple -->
    <footer class="py-10 border-t border-[#2563EB]/10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
        <p class="text-[#1E3A8A]/40 text-sm font-bold">&copy; 2026 Konekin. Kembangkan bisnismu bersama kami.</p>
        <div class="flex gap-8">
            <a href="#" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Bantuan</a>
            <a href="#" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Privasi</a>
            <a href="#" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Syarat & Ketentuan</a>
        </div>
    </footer>

</body>
</html>
