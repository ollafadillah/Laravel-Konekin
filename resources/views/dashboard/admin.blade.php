<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - Konekin</title>
    
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
    <x-dashboard-nav-admin />

    <!-- Main Content -->
    <main class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        
        <!-- Welcome Header -->
        <div class="mb-12">
            <h1 class="font-display text-3xl md:text-4xl font-bold text-[#1E3A8A] mb-2">Pusat Kendali Admin 🛡️</h1>
            <p class="text-[#1E3A8A]/60 font-medium text-lg">Kelola ekosistem Konekin dan pantau pertumbuhan komunitas.</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <!-- Stat 1 -->
            <div class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-[#EFF6FF] rounded-2xl group-hover:bg-[#2563EB] group-hover:text-white transition-colors text-[#2563EB]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 01-12 0v1zm0 0h6v-1a6 6 0 01-12 0v1zm0 0h6v-1a6 6 0 01-12 0v1z" /></svg>
                    </div>
                </div>
                <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Total Pengguna</h3>
                <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">{{ $totalUsers }}</p>
            </div>

            <!-- Stat 2 -->
            <div class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-[#F5F3FF] rounded-2xl group-hover:bg-[#7C3AED] group-hover:text-white transition-colors text-[#7C3AED]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    </div>
                </div>
                <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Creative Workers</h3>
                <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">{{ $totalCreativeWorkers }}</p>
            </div>

            <!-- Stat 3 -->
            <div class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-[#EFF6FF] rounded-2xl group-hover:bg-[#0A66C2] group-hover:text-white transition-colors text-[#0A66C2]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </div>
                </div>
                <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Mitra UMKM</h3>
                <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">{{ $totalUMKM }}</p>
            </div>

            <!-- Stat 4 -->
            <div class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-[#F0FDF4] rounded-2xl group-hover:bg-[#16A34A] group-hover:text-white transition-colors text-[#16A34A]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>
                <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Total Proyek</h3>
                <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">{{ $totalProjects }}</p>
            </div>
        </div>

        <!-- Admin Panels -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Quick Management -->
            <div class="space-y-6">
                <h2 class="font-display text-2xl font-bold text-[#1E3A8A]">Manajemen Cepat</h2>
                <div class="grid grid-cols-1 gap-4">
                    <a href="{{ route('admin.users') }}" class="bg-white p-6 rounded-[2.5rem] border border-[#2563EB]/5 hover:border-[#2563EB]/20 shadow-sm hover:shadow-xl transition-all cursor-pointer group">
                        <div class="flex items-center gap-4">
                            <div class="p-4 bg-[#EFF6FF] rounded-3xl group-hover:bg-[#2563EB] group-hover:text-white transition-all text-[#2563EB]">
                                <i class="fas fa-users-cog text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-[#1E3A8A]">Manajemen User</h4>
                                <p class="text-sm text-[#1E3A8A]/60">Pantau UMKM dan Creative Workers</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('admin.payments.index') }}" class="bg-white p-6 rounded-[2.5rem] border border-[#2563EB]/5 hover:border-[#2563EB]/20 shadow-sm hover:shadow-xl transition-all cursor-pointer group">
                        <div class="flex items-center gap-4">
                            <div class="p-4 bg-[#FEF3C7] rounded-3xl group-hover:bg-[#F59E0B] group-hover:text-white transition-all text-[#F59E0B]">
                                <i class="fas fa-file-invoice-dollar text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-[#1E3A8A]">Verifikasi Pembayaran</h4>
                                <p class="text-sm text-[#1E3A8A]/60">Kelola bukti pembayaran UMKM</p>
                            </div>
                        </div>
                    </a>
                    <div class="bg-white p-6 rounded-[2.5rem] border border-[#2563EB]/5 hover:border-[#2563EB]/20 shadow-sm hover:shadow-xl transition-all cursor-pointer group">
                        <div class="flex items-center gap-4">
                            <div class="p-4 bg-[#EFF6FF] rounded-3xl group-hover:bg-[#2563EB] group-hover:text-white transition-all text-[#2563EB]">
                                <i class="fas fa-shield-alt text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-[#1E3A8A]">Moderasi Konten</h4>
                                <p class="text-sm text-[#1E3A8A]/60">Pantau proyek dan portofolio terbaru</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Logs -->
            <div class="space-y-6">
                <h2 class="font-display text-2xl font-bold text-[#1E3A8A]">Aktivitas Terbaru</h2>
                <div class="bg-white rounded-[2.5rem] border border-[#2563EB]/5 overflow-hidden shadow-sm shadow-[#2563EB]/5">
                    <div class="p-6 divide-y divide-[#2563EB]/5">
                        <div class="py-4 flex gap-4">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center shrink-0">
                                <i class="fas fa-user-plus text-blue-600 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-[#1E3A8A]">User baru mendaftar</p>
                                <p class="text-xs text-[#1E3A8A]/40 font-bold">10 menit yang lalu sebagai UMKM</p>
                            </div>
                        </div>
                        <div class="py-4 flex gap-4">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                                <i class="fas fa-project-diagram text-emerald-600 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-[#1E3A8A]">Proyek baru dipublish</p>
                                <p class="text-xs text-[#1E3A8A]/40 font-bold">25 menit yang lalu oleh UMKM Maju Jaya</p>
                            </div>
                        </div>
                        <div class="py-4 flex gap-4">
                            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center shrink-0">
                                <i class="fas fa-briefcase text-purple-600 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-[#1E3A8A]">Lamaran proyek masuk</p>
                                <p class="text-xs text-[#1E3A8A]/40 font-bold">1 jam yang lalu oleh Kreator A</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 bg-[#F8FAFC] text-center border-t border-[#2563EB]/5">
                        <a href="#" class="text-xs font-bold text-[#2563EB] hover:underline">Lihat Log Lengkap</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer Simple -->
    <footer class="py-10 border-t border-[#2563EB]/10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
        <p class="text-[#1E3A8A]/40 text-sm font-bold">&copy; 2026 Konekin Admin Panel. Secure & Fast.</p>
        <div class="flex gap-8">
            <a href="#" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Bantuan Sistem</a>
            <a href="#" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Log Audit</a>
        </div>
    </footer>

</body>
</html>
