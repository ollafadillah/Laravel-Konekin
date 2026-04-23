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
    <x-dashboard-nav />

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
                <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">{{ $activeProjectsCount ?? 0 }}</p>
            </div>

            <!-- Stat 2 -->
            <div class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-[#FFF7ED] rounded-2xl group-hover:bg-[#EA580C] group-hover:text-white transition-colors text-[#EA580C]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>
                <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Pendapatan</h3>
                <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">Rp {{ number_format($user->escrowEarnings()->sum('amount'), 0, ',', '.') }}</p>
            </div>

            <!-- Stat 3 -->
            <div class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-[#F0FDF4] rounded-2xl group-hover:bg-[#16A34A] group-hover:text-white transition-colors text-[#16A34A]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </div>
                </div>
                <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Selesai</h3>
                <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">{{ $completedProjectsCount ?? 0 }}</p>
            </div>

            <!-- Stat 4 -->
            <div class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-[#FAF5FF] rounded-2xl group-hover:bg-[#9333EA] group-hover:text-white transition-colors text-[#9333EA]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                    </div>
                </div>
                <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Rating</h3>
                <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">{{ $averageRating > 0 ? $averageRating : '-' }}</p>
            </div>
        </div>

        <!-- Content Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Latest Projects (Left 2/3) -->
            <div class="lg:col-span-2 space-y-6">
                <div class="flex justify-between items-center">
                    <h2 class="font-display text-2xl font-bold text-[#1E3A8A]">Proyek Terbaru Untukmu</h2>
                    <a href="{{ route('projects.index') }}" class="text-[#2563EB] text-sm font-bold hover:underline">Lihat Semua</a>
                </div>

                @forelse($latestProjects as $project)
                    <div class="bg-white p-6 rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all flex flex-col md:flex-row gap-6 items-center">
                        <div class="w-full md:w-40 h-40 rounded-[2rem] overflow-hidden shrink-0 bg-slate-100">
                            @if(($project->media_type ?? null) === 'video' && !empty($project->media_url))
                                <video src="{{ $project->media_url }}" class="w-full h-full object-cover" muted playsinline></video>
                            @else
                                <img src="{{ $project->thumbnail }}" alt="Project" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="flex-grow">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-3 py-1 bg-[#EFF6FF] text-[#2563EB] text-[10px] font-extrabold uppercase tracking-widest rounded-full">{{ $project->category }}</span>
                                <span class="text-[#1E3A8A]/40 text-xs font-bold">{{ optional($project->created_at)->diffForHumans() ?? 'Baru saja' }}</span>
                                <span class="px-3 py-1 bg-slate-100 text-[#1E3A8A]/70 text-[10px] font-extrabold uppercase tracking-widest rounded-full">{{ $project->status_label ?? 'Belum Ada Apply' }}</span>
                            </div>
                            <h4 class="text-xl font-display font-bold text-[#1E3A8A] mb-2">{{ $project->title }}</h4>
                            <p class="text-[#1E3A8A]/60 text-sm line-clamp-2 mb-4 font-medium">{{ $project->description }}</p>
                            <p class="text-[#1E3A8A]/55 text-xs font-medium mb-4">Deadline: {{ \Illuminate\Support\Carbon::parse($project->deadline)->translatedFormat('d M Y') }}</p>
                            <div class="flex flex-wrap gap-2 mb-4">
                                <span class="px-3 py-1 bg-[#EFF6FF] text-[#2563EB] text-[10px] font-extrabold uppercase tracking-widest rounded-full">{{ $project->applications_count ?? 0 }} Apply</span>
                                <span class="px-3 py-1 bg-slate-100 text-[#1E3A8A]/70 text-[10px] font-extrabold uppercase tracking-widest rounded-full">{{ $project->progress_percentage ?? 0 }}% Progress</span>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-center gap-2">
                                    <img src="{{ $project->client_avatar }}" class="w-6 h-6 rounded-full">
                                    <span class="text-xs font-bold text-[#1E3A8A]/80">{{ $project->client_name }}</span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-[#1E3A8A] font-display font-bold">Rp {{ $project->budget }}</span>
                                    <a href="{{ route('projects.show', $project->id) }}" class="px-5 py-2.5 bg-[#1E3A8A] text-white text-xs font-bold rounded-xl hover:bg-[#2563EB] transition-colors inline-flex items-center justify-center">Lihat</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white p-8 rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm text-center">
                        <h4 class="text-xl font-display font-bold text-[#1E3A8A] mb-2">Belum Ada Proyek Baru</h4>
                        <p class="text-[#1E3A8A]/60 font-medium">Begitu UMKM mempublikasikan proyek baru, daftar terbaru akan muncul di sini.</p>
                    </div>
                @endforelse
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
