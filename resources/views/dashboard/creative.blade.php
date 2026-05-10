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
            @php
                $displayRating = ($ratingsCount ?? 0) > 0
                    ? rtrim(rtrim(number_format($averageRating ?? 0, 1, '.', ''), '0'), '.')
                    : '-';
            @endphp

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
            <button type="button" onclick="openRatingsModal()" class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group text-left w-full">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-[#FAF5FF] rounded-2xl group-hover:bg-[#9333EA] group-hover:text-white transition-colors text-[#9333EA]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                    </div>
                </div>
                <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Rating</h3>
                <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">
                    {{ $displayRating }}
                    @if(($ratingsCount ?? 0) > 0)
                        <span class="text-xl align-middle">({{ $ratingsCount }})</span>
                    @endif
                </p>
                <p class="text-xs font-bold text-[#1E3A8A]/35 mt-2">Klik untuk lihat preview ulasan</p>
            </button>
        </div>

        <!-- Invitations Section -->
        @if(isset($invitations) && $invitations->isNotEmpty())
            <div class="mb-12">
                <h2 class="font-display text-2xl font-bold text-[#1E3A8A] mb-6 flex items-center gap-3">
                    Undangan Proyek Baru 
                    <span class="px-2 py-1 bg-amber-100 text-amber-700 text-[10px] font-black uppercase tracking-widest rounded-lg">PENTING</span>
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($invitations as $invitation)
                        <div class="bg-gradient-to-br from-white to-[#EFF6FF] p-6 rounded-[2.5rem] border-2 border-[#2563EB]/20 shadow-xl shadow-[#2563EB]/5 relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-4">
                                <span class="px-3 py-1 bg-[#2563EB] text-white text-[10px] font-extrabold uppercase tracking-widest rounded-full">Undangan</span>
                            </div>
                            <div class="flex items-start gap-4 mb-6">
                                <img src="{{ $invitation->client_avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($invitation->client_name).'&background=2563EB&color=fff' }}" class="w-14 h-14 rounded-2xl shadow-md border-2 border-white">
                                <div>
                                    <h4 class="text-xl font-display font-bold text-[#1E3A8A] mb-1">{{ $invitation->title }}</h4>
                                    <p class="text-sm text-[#1E3A8A]/60 font-medium">Dari: <span class="font-bold text-[#1E3A8A]">{{ $invitation->client_name }}</span></p>
                                </div>
                            </div>
                            <div class="space-y-3 mb-6">
                                <div class="flex items-center gap-2 text-sm font-medium text-[#1E3A8A]/70">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    Budget: <span class="font-bold text-[#1E3A8A]">Rp {{ number_format($invitation->budget, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm font-medium text-[#1E3A8A]/70">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z" /></svg>
                                    Deadline: <span class="font-bold text-[#1E3A8A]">{{ \Illuminate\Support\Carbon::parse($invitation->deadline)->translatedFormat('d M Y') }}</span>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <form action="{{ route('projects.accept', $invitation->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full py-3 bg-[#1E3A8A] text-white rounded-2xl text-xs font-extrabold uppercase tracking-wider hover:bg-[#2563EB] transition-all shadow-lg shadow-[#1E3A8A]/10">Terima</button>
                                </form>
                                <form action="{{ route('projects.reject', $invitation->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full py-3 bg-white text-red-500 border border-red-100 rounded-2xl text-xs font-extrabold uppercase tracking-wider hover:bg-red-50 transition-all">Tolak</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

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

                <div>
                    <h2 class="font-display text-2xl font-bold text-[#1E3A8A] mb-6">Ulasan Terbaru</h2>
                    <div class="bg-white rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm overflow-hidden">
                        @forelse($recentRatings as $rating)
                            <div class="p-5 {{ !$loop->last ? 'border-b border-[#2563EB]/5' : '' }}">
                                <div class="flex items-start gap-4">
                                    <img src="{{ optional($rating->fromUser)->profile_photo ?? 'https://ui-avatars.com/api/?name='.urlencode(optional($rating->fromUser)->name ?? 'UMKM').'&background=random' }}" alt="{{ optional($rating->fromUser)->name ?? 'UMKM' }}" class="w-12 h-12 rounded-2xl object-cover shrink-0">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-3 mb-2">
                                            <div>
                                                <p class="font-bold text-[#1E3A8A]">{{ optional($rating->fromUser)->name ?? 'UMKM' }}</p>
                                                <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-[#2563EB]">{{ optional($rating->project)->title ?? $rating->project_title_snapshot ?? 'Proyek diarsipkan' }}</p>
                                            </div>
                                            <div class="flex items-center gap-0.5 shrink-0">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-3.5 h-3.5 {{ $i <= $rating->rating ? 'text-amber-400' : 'text-slate-200' }} fill-current" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.388 2.46a1 1 0 00-.364 1.118l1.287 3.97c.3.921-.755 1.688-1.54 1.118l-3.388-2.46a1 1 0 00-1.175 0l-3.388 2.46c-.784.57-1.838-.197-1.539-1.118l1.287-3.97a1 1 0 00-.364-1.118L2.245 9.397c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.97z"/>
                                                    </svg>
                                                @endfor
                                            </div>
                                        </div>
                                        <p class="text-sm text-[#1E3A8A]/65 leading-7 font-medium">
                                            {{ $rating->comment ?: 'UMKM ini belum menambahkan komentar.' }}
                                        </p>
                                        <p class="text-[11px] text-[#1E3A8A]/40 font-bold mt-2">{{ optional($rating->created_at)->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center">
                                <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-[#EFF6FF] flex items-center justify-center text-[#2563EB]">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                </div>
                                <h4 class="font-display text-xl font-bold text-[#1E3A8A] mb-2">Belum Ada Ulasan</h4>
                                <p class="text-sm text-[#1E3A8A]/60 font-medium">Begitu UMKM memberi rating setelah proyek selesai, ulasan akan muncul di sini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div id="ratings-modal" class="fixed inset-0 z-[140] hidden">
        <div class="absolute inset-0 bg-slate-900/55 backdrop-blur-md" onclick="closeRatingsModal()"></div>

        <div class="relative min-h-screen flex items-center justify-center px-4 py-8">
            <div class="w-full max-w-2xl rounded-[2.5rem] bg-white border border-white/80 shadow-2xl shadow-[#1E3A8A]/20 overflow-hidden relative">
                <div class="px-8 sm:px-10 pt-10 pb-8 bg-[radial-gradient(circle_at_top_left,_rgba(37,99,235,0.18),_transparent_42%),linear-gradient(135deg,#EFF6FF_0%,#FFFFFF_55%,#F8FAFC_100%)]">
                    <button type="button" onclick="closeRatingsModal()" class="absolute top-5 right-5 w-10 h-10 rounded-full bg-white/90 border border-[#2563EB]/10 text-[#1E3A8A] hover:bg-[#EFF6FF] transition-all">
                        <span class="sr-only">Tutup</span>
                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-[#2563EB]/10 shadow-sm mb-6">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#9333EA]"></span>
                        <span class="text-[11px] font-extrabold uppercase tracking-[0.22em] text-[#9333EA]">Rating Creative Worker</span>
                    </div>

                    <h2 class="font-display text-3xl sm:text-[2rem] font-bold text-[#1E3A8A] leading-tight mb-3 flex items-center gap-3">
                        <span>{{ $displayRating }}</span>
                        @if(($ratingsCount ?? 0) > 0)
                            <span class="text-lg text-[#2563EB]/80">({{ $ratingsCount }})</span>
                        @endif
                    </h2>
                    <p class="text-[#1E3A8A]/65 font-medium leading-relaxed text-sm sm:text-base">
                        Preview ini menampilkan ulasan terbaru yang kamu terima setelah proyek selesai.
                    </p>
                </div>

                <div class="px-8 sm:px-10 py-8 max-h-[60vh] overflow-y-auto space-y-4">
                    @forelse($recentRatings as $rating)
                        <article class="rounded-[2rem] border border-[#2563EB]/8 bg-[#F8FAFC] p-5">
                            <div class="flex items-start gap-4">
                                <img src="{{ optional($rating->fromUser)->profile_photo ?? 'https://ui-avatars.com/api/?name='.urlencode(optional($rating->fromUser)->name ?? 'UMKM').'&background=random' }}" alt="{{ optional($rating->fromUser)->name ?? 'UMKM' }}" class="w-12 h-12 rounded-2xl object-cover shrink-0">
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 mb-3">
                                        <div>
                                            <p class="font-bold text-[#1E3A8A]">{{ optional($rating->fromUser)->name ?? 'UMKM' }}</p>
                                            <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-[#2563EB]">{{ optional($rating->project)->title ?? $rating->project_title_snapshot ?? 'Proyek diarsipkan' }}</p>
                                        </div>
                                        <p class="text-[11px] text-[#1E3A8A]/40 font-bold">{{ optional($rating->created_at)->diffForHumans() }}</p>
                                    </div>
                                    <div class="flex items-center gap-0.5 mb-3">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-3.5 h-3.5 {{ $i <= $rating->rating ? 'text-amber-400' : 'text-slate-200' }} fill-current" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.388 2.46a1 1 0 00-.364 1.118l1.287 3.97c.3.921-.755 1.688-1.54 1.118l-3.388-2.46a1 1 0 00-1.175 0l-3.388 2.46c-.784.57-1.838-.197-1.539-1.118l1.287-3.97a1 1 0 00-.364-1.118L2.245 9.397c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.97z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    <p class="text-sm text-[#1E3A8A]/65 leading-7 font-medium">
                                        {{ $rating->comment ?: 'UMKM ini belum menambahkan komentar.' }}
                                    </p>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-[2rem] bg-[#F8FAFC] border border-dashed border-[#2563EB]/15 p-8 text-center">
                            <div class="w-14 h-14 rounded-2xl bg-white shadow-sm mx-auto mb-4 flex items-center justify-center">
                                <svg class="w-7 h-7 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 1.24 1.24 1.81 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                            </div>
                            <h3 class="font-display text-xl font-bold text-[#1E3A8A] mb-2">Belum Ada Ulasan</h3>
                            <p class="text-[#1E3A8A]/60 font-medium">Begitu UMKM memberi rating setelah proyek selesai, ulasan akan tampil di sini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Simple -->
    <footer class="py-10 border-t border-[#2563EB]/10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
        <p class="text-[#1E3A8A]/40 text-sm font-bold">&copy; 2026 Konekin. Wujudkan ide kreatifmu.</p>
        <div class="flex gap-8">
            <a href="#" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Bantuan</a>
            <a href="#" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Privasi</a>
            <a href="#" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Syarat & Ketentuan</a>
        </div>
    </footer>

    <script>
        function openRatingsModal() {
            const modal = document.getElementById('ratings-modal');
            if (!modal) return;

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeRatingsModal() {
            const modal = document.getElementById('ratings-modal');
            if (!modal) return;

            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        window.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeRatingsModal();
            }
        });
    </script>

</body>
</html>
