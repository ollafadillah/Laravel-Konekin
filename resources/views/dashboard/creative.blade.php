<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Creative - Konekin</title>
    
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
    <nav class="fixed w-full top-0 z-50 glass border-b border-[#2563EB]/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group shrink-0">
                    <div class="relative w-10 h-10 flex items-center justify-center">
                        <div class="absolute inset-0 bg-[#2563EB] rounded-xl rotate-3 group-hover:rotate-6 transition-all shadow-lg shadow-[#2563EB]/20"></div>
                        <span class="relative text-white font-display font-bold text-lg">K</span>
                    </div>
                    <span class="font-display font-bold text-xl tracking-tight">Konekin<span class="text-[#2563EB]">.</span></span>
                </a>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center bg-white/50 px-6 py-2 rounded-full border border-white/80 shadow-sm gap-1">
                    <a href="{{ route('dashboard.creative') }}" class="px-4 py-2 bg-[#2563EB] text-white rounded-full text-sm font-bold shadow-md shadow-[#2563EB]/20 transition-all">Dashboard</a>
                    <a href="#" class="px-4 py-2 text-[#1E3A8A]/70 hover:text-[#2563EB] rounded-full text-sm font-bold transition-all">Cari Proyek</a>
                    <a href="#" class="px-4 py-2 text-[#1E3A8A]/70 hover:text-[#2563EB] rounded-full text-sm font-bold transition-all">Portfolio</a>
                    <a href="#" class="px-4 py-2 text-[#1E3A8A]/70 hover:text-[#2563EB] rounded-full text-sm font-bold transition-all">Pesan</a>
                </div>

                <!-- Right Side: Notification & Profile -->
                <div class="flex items-center gap-4">
                    <!-- Notification Bell -->
                    <button class="relative p-2.5 bg-white border border-[#2563EB]/10 rounded-xl hover:bg-[#EFF6FF] transition-all group">
                        <svg class="w-6 h-6 text-[#1E3A8A]/70 group-hover:text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="absolute top-2.5 right-2.5 flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                        </span>
                    </button>

                    <!-- Profile -->
                    <a href="{{ route('profile.index') }}" class="flex items-center gap-3 pl-4 border-l border-[#2563EB]/10 group/profile cursor-pointer hover:opacity-80 transition-all">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold text-[#1E3A8A] leading-none group-hover/profile:text-[#2563EB] transition-colors">{{ $user->name }}</p>
                            <p class="text-[10px] font-bold text-[#2563EB] uppercase tracking-wider mt-1">Creative Worker</p>
                        </div>
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#2563EB] to-[#0A66C2] p-0.5 shadow-lg shadow-[#2563EB]/20 group-hover/profile:scale-105 transition-transform">
                            <img src="{{ $user->profile_photo ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random' }}" alt="Profile" class="w-full h-full object-cover rounded-[10px] border-2 border-white">
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        
        <!-- Welcome Header -->
        <div class="mb-12">
            <h1 class="font-display text-3xl md:text-4xl font-bold text-[#1E3A8A] mb-2">Halo, {{ $user->name }}! 👋</h1>
            <p class="text-[#1E3A8A]/60 font-medium text-lg">Selamat datang kembali di dashboard kreatifmu. Mari buat karya luar biasa hari ini.</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <!-- Stat 1 -->
            <div class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-[#EFF6FF] rounded-2xl group-hover:bg-[#2563EB] group-hover:text-white transition-colors text-[#2563EB]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                    </div>
                    <span class="text-green-500 text-xs font-bold bg-green-50 px-2.5 py-1 rounded-full">+2 Proyek</span>
                </div>
                <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Proyek Aktif</h3>
                <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">12</p>
            </div>

            <!-- Stat 2 -->
            <div class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-[#FFF7ED] rounded-2xl group-hover:bg-[#EA580C] group-hover:text-white transition-colors text-[#EA580C]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>
                <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Pendapatan</h3>
                <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">Rp 8.5M</p>
            </div>

            <!-- Stat 3 -->
            <div class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-[#F0FDF4] rounded-2xl group-hover:bg-[#16A34A] group-hover:text-white transition-colors text-[#16A34A]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </div>
                </div>
                <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Selesai</h3>
                <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">48</p>
            </div>

            <!-- Stat 4 -->
            <div class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-[#FAF5FF] rounded-2xl group-hover:bg-[#9333EA] group-hover:text-white transition-colors text-[#9333EA]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                    </div>
                </div>
                <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Rating</h3>
                <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">4.9</p>
            </div>
        </div>

        <!-- Content Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Latest Projects (Left 2/3) -->
            <div class="lg:col-span-2 space-y-6">
                <div class="flex justify-between items-center">
                    <h2 class="font-display text-2xl font-bold text-[#1E3A8A]">Proyek Terbaru Untukmu</h2>
                    <a href="#" class="text-[#2563EB] text-sm font-bold hover:underline">Lihat Semua</a>
                </div>

                <!-- Project Card 1 -->
                <div class="bg-white p-6 rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all flex flex-col md:flex-row gap-6 items-center">
                    <div class="w-full md:w-40 h-40 rounded-[2rem] overflow-hidden shrink-0">
                        <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?q=80&w=2070&auto=format&fit=crop" alt="Project" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-grow">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-3 py-1 bg-[#EFF6FF] text-[#2563EB] text-[10px] font-extrabold uppercase tracking-widest rounded-full">Branding</span>
                            <span class="text-[#1E3A8A]/40 text-xs font-bold">2 jam yang lalu</span>
                        </div>
                        <h4 class="text-xl font-display font-bold text-[#1E3A8A] mb-2">Redesain Identitas Visual "Kopi Kita"</h4>
                        <p class="text-[#1E3A8A]/60 text-sm line-clamp-2 mb-4 font-medium">Kami membutuhkan desainer kreatif untuk memperbarui logo dan kemasan produk kopi artisan kami agar lebih modern.</p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <img src="https://ui-avatars.com/api/?name=Kopi+Kita&background=2563EB&color=fff" class="w-6 h-6 rounded-full">
                                <span class="text-xs font-bold text-[#1E3A8A]/80">UMKM Kopi Kita</span>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="text-[#1E3A8A] font-display font-bold">Rp 3.500.000</span>
                                <button class="px-5 py-2.5 bg-[#1E3A8A] text-white text-xs font-bold rounded-xl hover:bg-[#2563EB] transition-colors">Ajukan</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Project Card 2 -->
                <div class="bg-white p-6 rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all flex flex-col md:flex-row gap-6 items-center">
                    <div class="w-full md:w-40 h-40 rounded-[2rem] overflow-hidden shrink-0">
                        <img src="https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?q=80&w=1974&auto=format&fit=crop" alt="Project" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-grow">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-3 py-1 bg-[#FDF2F8] text-[#DB2777] text-[10px] font-extrabold uppercase tracking-widest rounded-full">Social Media</span>
                            <span class="text-[#1E3A8A]/40 text-xs font-bold">5 jam yang lalu</span>
                        </div>
                        <h4 class="text-xl font-display font-bold text-[#1E3A8A] mb-2">Konten Instagram & TikTok "Batik Solo"</h4>
                        <p class="text-[#1E3A8A]/60 text-sm line-clamp-2 mb-4 font-medium">Mencari content creator untuk mengelola feed dan membuat video pendek kreatif untuk mempromosikan koleksi terbaru.</p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <img src="https://ui-avatars.com/api/?name=Batik+Solo&background=DB2777&color=fff" class="w-6 h-6 rounded-full">
                                <span class="text-xs font-bold text-[#1E3A8A]/80">UMKM Batik Solo</span>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="text-[#1E3A8A] font-display font-bold">Rp 2.000.000</span>
                                <button class="px-5 py-2.5 bg-[#1E3A8A] text-white text-xs font-bold rounded-xl hover:bg-[#2563EB] transition-colors">Ajukan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Messages & Notifications (Right 1/3) -->
            <div class="space-y-8">
                <div>
                    <h2 class="font-display text-2xl font-bold text-[#1E3A8A] mb-6">Pesan Terbaru</h2>
                    <div class="bg-white rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm divide-y divide-[#2563EB]/5">
                        <!-- Message 1 -->
                        <div class="p-5 flex gap-4 hover:bg-[#F8FAFC] transition-colors cursor-pointer">
                            <img src="https://ui-avatars.com/api/?name=Budi&background=random" class="w-12 h-12 rounded-2xl shadow-sm">
                            <div class="flex-grow overflow-hidden">
                                <div class="flex justify-between items-center mb-1">
                                    <h5 class="text-sm font-bold text-[#1E3A8A]">Budi - Kopi Kita</h5>
                                    <span class="text-[10px] text-[#1E3A8A]/40 font-bold">14:20</span>
                                </div>
                                <p class="text-xs text-[#1E3A8A]/60 line-clamp-1 font-medium">Halo mas, apakah bisa revisi bagian warna logo?</p>
                            </div>
                        </div>
                        <!-- Message 2 -->
                        <div class="p-5 flex gap-4 hover:bg-[#F8FAFC] transition-colors cursor-pointer">
                            <img src="https://ui-avatars.com/api/?name=Siska&background=random" class="w-12 h-12 rounded-2xl shadow-sm">
                            <div class="flex-grow overflow-hidden">
                                <div class="flex justify-between items-center mb-1">
                                    <h5 class="text-sm font-bold text-[#1E3A8A]">Siska - Batik Solo</h5>
                                    <span class="text-[10px] text-[#1E3A8A]/40 font-bold">Kemarin</span>
                                </div>
                                <p class="text-xs text-[#1E3A8A]/60 line-clamp-1 font-medium">Proposalnya sudah saya terima ya, terima kasih.</p>
                            </div>
                        </div>
                        <div class="p-4 text-center">
                            <a href="#" class="text-xs font-bold text-[#2563EB] hover:underline">Semua Pesan</a>
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="font-display text-2xl font-bold text-[#1E3A8A] mb-6">Progres Portofolio</h2>
                    <div class="bg-[#1E3A8A] p-8 rounded-[2.5rem] text-white shadow-xl shadow-[#1E3A8A]/20 relative overflow-hidden group">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                        <div class="relative z-10">
                            <p class="text-xs font-bold text-white/60 uppercase tracking-widest mb-2">Status Profil</p>
                            <h4 class="text-2xl font-display font-bold mb-6">85% Lengkap</h4>
                            <div class="w-full h-3 bg-white/20 rounded-full mb-6 overflow-hidden">
                                <div class="w-[85%] h-full bg-gradient-to-r from-[#2563EB] to-white rounded-full"></div>
                            </div>
                            <a href="{{ route('profile.index') }}" class="block w-full py-3 bg-white text-[#1E3A8A] text-center rounded-2xl text-xs font-extrabold uppercase tracking-wider hover:bg-[#EFF6FF] transition-all">Lengkapi Profil</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer Simple -->
    <footer class="py-10 border-t border-[#2563EB]/10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
        <p class="text-[#1E3A8A]/40 text-sm font-bold">&copy; 2026 Konekin. Wujudkan ide kreatifmu.</p>
        <div class="flex gap-8">
            <a href="#" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Bantuan</a>
            <a href="#" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Privasi</a>
            <a href="#" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Syarat & Ketentuan</a>
        </div>
    </footer>

</body>
</html>
