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
            background-color: #F4F7FE;
            overflow-x: hidden;
        }
        .font-display {
            font-family: 'Space Grotesk', sans-serif;
        }
        .glass-bento {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.03);
        }
        
        /* Animated Background Orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: -1;
            animation: float 20s infinite ease-in-out alternate;
        }
        .orb-1 {
            width: 400px; height: 400px;
            background: rgba(37, 99, 235, 0.14); /* Konekin Blue */
            top: -100px; left: -100px;
        }
        .orb-2 {
            width: 500px; height: 500px;
            background: rgba(14, 165, 233, 0.12); /* Sky Blue */
            bottom: 20%; right: -150px;
            animation-delay: -5s;
        }
        .orb-3 {
            width: 400px; height: 400px;
            background: rgba(30, 58, 138, 0.10); /* Professional Navy */
            top: 40%; left: 30%;
            animation-delay: -10s;
        }
        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(60px, 60px) scale(1.1); }
            100% { transform: translate(-60px, 30px) scale(0.9); }
        }
        .bento-hover {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .bento-hover:hover {
            transform: translateY(-8px) scale(1.01);
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.06);
            background: rgba(255, 255, 255, 0.85);
        }
        .text-gradient {
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="antialiased text-[#1E3A8A] relative min-h-screen">
    
    <!-- Background Orbs -->
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <!-- Navbar -->
    <x-dashboard-nav />

    <!-- Main Content -->
    <main class="pt-32 pb-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto relative z-10">
        
        <!-- Welcome Header & Primary Stats Bento -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">
            
            <!-- Welcome Card (Span 8) -->
            <div class="lg:col-span-8 glass-bento rounded-[2.5rem] p-8 md:p-12 relative overflow-hidden bento-hover group">
                <div class="absolute top-0 right-0 w-80 h-80 bg-gradient-to-br from-[#2563EB] to-[#0EA5E9] rounded-full blur-3xl opacity-10 group-hover:opacity-25 transition-opacity duration-700"></div>
                <div class="absolute -bottom-20 -left-20 w-60 h-60 bg-gradient-to-tr from-[#1E3A8A] to-[#38BDF8] rounded-full blur-3xl opacity-10 group-hover:opacity-20 transition-opacity duration-700"></div>
                
                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/80 border border-white shadow-sm mb-6">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-600">Online & Siap Bekerja</span>
                    </div>

                    <h1 class="font-display text-4xl md:text-[3.5rem] leading-[1.1] font-bold mb-4 tracking-tight text-slate-800">
                        Waktunya Berkreasi,<br>
                        <span class="bg-gradient-to-r from-[#1E3A8A] via-[#2563EB] to-[#0EA5E9] text-gradient">{{ $user->name }}! &#10024;</span>
                    </h1>
                    <p class="text-slate-500 font-medium text-lg max-w-xl mb-10 leading-relaxed">
                        Kanvasmu sudah siap. Mari wujudkan ide-ide liar menjadi karya luar biasa hari ini. Dunia menunggu magismu.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('projects.index') }}" class="px-8 py-4 bg-[#1E3A8A] text-white rounded-full font-bold shadow-xl shadow-[#1E3A8A]/20 hover:scale-105 hover:bg-[#2563EB] transition-all">
                            Cari Inspirasi Proyek
                        </a>
                        <a href="{{ route('portfolio.index') }}" class="px-8 py-4 bg-white/80 text-slate-800 rounded-full font-bold shadow-lg shadow-slate-200/50 hover:scale-105 transition-all border border-white">
                            Update Portofolio
                        </a>
                    </div>
                </div>
            </div>

            <!-- Earnings Card (Span 4) -->
            <div class="lg:col-span-4 rounded-[2.5rem] p-8 relative overflow-hidden bento-hover bg-gradient-to-br from-[#0F172A] via-[#1E3A8A] to-[#0A66C2] text-white shadow-2xl shadow-[#2563EB]/20 border border-[#60A5FA]/25">
                <div class="absolute top-0 right-0 w-40 h-40 bg-[#38BDF8]/25 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-32 h-32 bg-[#2563EB]/25 rounded-full blur-2xl"></div>
                
                <div class="relative z-10 flex flex-col h-full justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-3.5 bg-white/10 backdrop-blur-md rounded-2xl border border-white/10">
                                <svg class="w-6 h-6 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <span class="px-3 py-1.5 bg-white/5 backdrop-blur-md rounded-full text-[10px] font-black uppercase tracking-widest text-sky-100 border border-white/10">Total</span>
                        </div>
                        <h3 class="text-sky-100 text-sm font-bold uppercase tracking-wider mt-4 mb-1">Pendapatan Diterima</h3>
                    </div>
                    <div>
                        <p class="text-[2.5rem] leading-none font-display font-bold text-white mb-2">Rp {{ number_format($user->escrowEarnings()->sum('amount'), 0, ',', '.') }}</p>
                        <div class="flex items-center gap-2 text-emerald-400 text-sm font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            Terus berkarya!
                        </div>
                    </div>
                </div>
            </div>

        </div>

        @php
            $displayRating = ($ratingsCount ?? 0) > 0
                ? rtrim(rtrim(number_format($averageRating ?? 0, 1, '.', ''), '0'), '.')
                : '-';
        @endphp

        <!-- Secondary Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <!-- Active Projects -->
            <div class="glass-bento p-6 rounded-[2.5rem] bento-hover flex items-center gap-5">
                <div class="w-16 h-16 rounded-[1.25rem] bg-gradient-to-br from-[#2563EB] to-[#0A66C2] flex items-center justify-center shrink-0 shadow-lg shadow-[#2563EB]/30 text-white">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                </div>
                <div>
                    <h3 class="text-slate-400 text-xs font-black uppercase tracking-[0.15em] mb-1">Proyek Aktif</h3>
                    <p class="text-3xl font-display font-bold text-slate-800">{{ $activeProjectsCount ?? 0 }}</p>
                </div>
            </div>

            <!-- Completed Projects -->
            <div class="glass-bento p-6 rounded-[2.5rem] bento-hover flex items-center gap-5">
                <div class="w-16 h-16 rounded-[1.25rem] bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center shrink-0 shadow-lg shadow-emerald-500/30 text-white">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                </div>
                <div>
                    <h3 class="text-slate-400 text-xs font-black uppercase tracking-[0.15em] mb-1">Telah Selesai</h3>
                    <p class="text-3xl font-display font-bold text-slate-800">{{ $completedProjectsCount ?? 0 }}</p>
                </div>
            </div>

            <!-- Rating -->
            <button type="button" onclick="openRatingsModal()" class="glass-bento p-6 rounded-[2.5rem] bento-hover flex items-center gap-5 text-left w-full group relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-amber-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="w-16 h-16 rounded-[1.25rem] bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shrink-0 shadow-lg shadow-amber-500/30 text-white relative z-10 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                </div>
                <div class="relative z-10">
                    <h3 class="text-slate-400 text-xs font-black uppercase tracking-[0.15em] mb-1">Rating Kualitas</h3>
                    <div class="flex items-baseline gap-2">
                        <p class="text-3xl font-display font-bold text-slate-800">{{ $displayRating }}</p>
                        @if(($ratingsCount ?? 0) > 0)
                            <span class="text-slate-500 font-bold">({{ $ratingsCount }})</span>
                        @endif
                    </div>
                </div>
            </button>
        </div>

        <!-- Invitations Section -->
        @if(isset($invitations) && $invitations->isNotEmpty())
            <div class="mb-14">
                <div class="flex items-center gap-4 mb-8">
                    <h2 class="font-display text-3xl font-bold text-slate-800">Undangan Eksklusif</h2>
                    <span class="px-4 py-1.5 bg-gradient-to-r from-amber-400 to-orange-500 text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg shadow-orange-500/30 animate-pulse">Hot</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($invitations as $invitation)
                        <div class="glass-bento p-6 rounded-[2.5rem] bento-hover border-2 border-amber-200/50 relative overflow-hidden flex flex-col group">
                            <div class="absolute -right-10 -top-10 w-32 h-32 bg-amber-400/20 rounded-full blur-2xl group-hover:bg-amber-400/30 transition-colors"></div>
                            <div class="flex items-start gap-4 mb-6 relative z-10">
                                <img src="{{ $invitation->client_avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($invitation->client_name).'&background=f59e0b&color=fff' }}" class="w-14 h-14 rounded-full shadow-md border-2 border-white object-cover">
                                <div>
                                    <h4 class="text-lg font-display font-bold text-slate-800 line-clamp-1 group-hover:text-amber-600 transition-colors">{{ $invitation->title }}</h4>
                                    <p class="text-sm text-slate-500 font-medium">{{ $invitation->client_name }}</p>
                                </div>
                            </div>
                            <div class="space-y-3 mb-6 flex-grow relative z-10">
                                <div class="flex justify-between items-center px-4 py-3 bg-white/60 rounded-2xl border border-white shadow-sm">
                                    <span class="text-[10px] font-black tracking-widest text-slate-400 uppercase">Budget</span>
                                    <span class="font-display font-bold text-slate-800">Rp {{ number_format($invitation->budget, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center px-4 py-3 bg-white/60 rounded-2xl border border-white shadow-sm">
                                    <span class="text-[10px] font-black tracking-widest text-slate-400 uppercase">Deadline</span>
                                    <span class="font-bold text-slate-700 text-sm">{{ \Illuminate\Support\Carbon::parse($invitation->deadline)->translatedFormat('d M Y') }}</span>
                                </div>
                            </div>
                            <div class="flex gap-3 relative z-10">
                                <form action="{{ route('projects.accept', $invitation->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full py-3.5 bg-slate-900 text-white rounded-2xl text-xs font-bold uppercase tracking-wider hover:bg-black transition-all shadow-xl shadow-slate-900/20">Terima</button>
                                </form>
                                <form action="{{ route('projects.reject', $invitation->id) }}" method="POST" class="w-14">
                                    @csrf
                                    <button type="submit" class="w-full h-full flex items-center justify-center bg-white text-red-500 border border-red-100 rounded-2xl hover:bg-red-50 hover:text-red-600 transition-all shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Content Sections -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            
            <!-- Latest Projects (Left 2/3) -->
            <div class="xl:col-span-2 space-y-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-8 gap-4">
                    <div>
                        <h2 class="font-display text-3xl font-bold text-slate-800">Panggung Inspirasi</h2>
                        <p class="text-slate-500 font-medium mt-2">Proyek terbaru yang siap menanti sentuhan ajaibmu.</p>
                    </div>
                    <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-full hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm shrink-0">
                        Eksplor Semua
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($latestProjects as $project)
                        <a href="{{ route('projects.show', $project->id) }}" class="glass-bento rounded-[2.5rem] p-4 bento-hover flex flex-col group block">
                            <div class="relative w-full h-52 rounded-[2rem] overflow-hidden mb-5">
                                @if(($project->media_type ?? null) === 'video' && !empty($project->media_url))
                                    <video src="{{ $project->media_url }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" muted playsinline autoplay loop></video>
                                @else
                                    <img src="{{ $project->thumbnail }}" alt="Project" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="absolute top-4 left-4">
                                    <span class="px-4 py-2 bg-white/90 backdrop-blur-md text-slate-800 text-[10px] font-black uppercase tracking-widest rounded-full shadow-sm">{{ $project->category }}</span>
                                </div>
                                <div class="absolute top-4 right-4">
                                    <div class="w-8 h-8 rounded-full bg-white/90 backdrop-blur-md flex items-center justify-center shadow-sm">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="px-2 flex-grow flex flex-col">
                                <div class="flex items-center gap-3 mb-3">
                                    <img src="{{ $project->client_avatar }}" class="w-6 h-6 rounded-full object-cover border border-slate-200">
                                    <span class="text-xs font-bold text-slate-500">{{ $project->client_name }}</span>
                                    <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                    <span class="text-xs font-bold text-slate-400">{{ optional($project->created_at)->diffForHumans() ?? 'Baru saja' }}</span>
                                </div>
                                
                                <h4 class="text-xl font-display font-bold text-slate-800 mb-2 line-clamp-2 group-hover:text-[#2563EB] transition-colors">{{ $project->title }}</h4>
                                <p class="text-sm text-slate-500 line-clamp-2 mb-4">{{ $project->description }}</p>
                                
                                <div class="mt-auto pt-4 flex items-center justify-between border-t border-slate-100">
                                    <div>
                                        <span class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Budget</span>
                                        <span class="text-lg font-display font-extrabold text-slate-800">Rp {{ $project->budget }}</span>
                                    </div>
                                    <div class="w-10 h-10 rounded-full bg-[#1E3A8A] text-white flex items-center justify-center group-hover:bg-[#2563EB] group-hover:shadow-lg group-hover:shadow-[#2563EB]/30 transition-all">
                                        <svg class="w-4 h-4 -rotate-45 group-hover:rotate-0 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="md:col-span-2 glass-bento p-16 rounded-[3rem] text-center border-dashed border-2 border-slate-200">
                            <div class="w-24 h-24 mx-auto mb-6 bg-white rounded-[2rem] shadow-sm flex items-center justify-center">
                                <svg class="w-10 h-10 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                            <h4 class="text-2xl font-display font-bold text-slate-800 mb-3">Kanvas Masih Kosong</h4>
                            <p class="text-slate-500 font-medium max-w-md mx-auto">Begitu UMKM mempublikasikan proyek baru yang sesuai dengan keahlianmu, daftar inspirasi akan muncul di sini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="space-y-8">
                
                <!-- Portfolio Progress -->
                <div class="bg-gradient-to-br from-[#2563EB] via-[#0A66C2] to-[#1E3A8A] p-8 rounded-[2.5rem] text-white shadow-2xl shadow-[#2563EB]/20 relative overflow-hidden group bento-hover">
                    <div class="absolute -top-20 -right-20 w-64 h-64 bg-white/10 rounded-full blur-3xl opacity-50 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-6">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20 shadow-inner">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-[10px] font-black uppercase tracking-widest text-white border border-white/20">Level Up</span>
                        </div>
                        <h4 class="text-3xl font-display font-bold mb-2">85% <span class="text-lg text-sky-100 font-medium">Lengkap</span></h4>
                        <p class="text-sm text-blue-50 mb-8 leading-relaxed">Portofoliomu sudah terlihat luar biasa. Tambahkan sedikit lagi untuk menarik lebih banyak klien UMKM.</p>
                        
                        <div class="w-full h-2 bg-black/20 rounded-full mb-8 overflow-hidden">
                            <div class="w-[85%] h-full bg-gradient-to-r from-emerald-300 to-emerald-400 rounded-full relative">
                                <div class="absolute inset-0 bg-white/30 w-full animate-pulse"></div>
                            </div>
                        </div>
                        <a href="{{ route('profile.index') }}" class="block w-full py-4 bg-white text-[#1E3A8A] text-center rounded-2xl text-sm font-bold hover:bg-[#EFF6FF] transition-all shadow-xl shadow-black/10">Sempurnakan Profil</a>
                    </div>
                </div>

                <!-- Recent Ratings -->
                <div class="glass-bento rounded-[2.5rem] p-8 bento-hover">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="font-display text-xl font-bold text-slate-800">Suara Klien</h2>
                        <span class="p-2 bg-[#EFF6FF] text-[#2563EB] rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </span>
                    </div>
                    <div class="space-y-4">
                        @forelse($recentRatings as $rating)
                            <div class="bg-white/70 backdrop-blur-md p-5 rounded-[2rem] border border-white shadow-sm hover:bg-white hover:shadow-md transition-all">
                                <div class="flex gap-4">
                                    <img src="{{ optional($rating->fromUser)->profile_photo ?? 'https://ui-avatars.com/api/?name='.urlencode(optional($rating->fromUser)->name ?? 'UMKM').'&background=random' }}" class="w-10 h-10 rounded-full object-cover shrink-0 border-2 border-white shadow-sm">
                                    <div class="min-w-0 flex-grow">
                                        <div class="flex justify-between items-start mb-1">
                                            <p class="font-bold text-slate-800 text-sm truncate pr-2">{{ optional($rating->fromUser)->name ?? 'UMKM' }}</p>
                                            <div class="flex items-center gap-0.5 shrink-0">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-3 h-3 {{ $i <= $rating->rating ? 'text-amber-400' : 'text-slate-200' }} fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.388 2.46a1 1 0 00-.364 1.118l1.287 3.97c.3.921-.755 1.688-1.54 1.118l-3.388-2.46a1 1 0 00-1.175 0l-3.388 2.46c-.784.57-1.838-.197-1.539-1.118l1.287-3.97a1 1 0 00-.364-1.118L2.245 9.397c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.97z"/></svg>
                                                @endfor
                                            </div>
                                        </div>
                                        <p class="text-[10px] font-black uppercase tracking-widest text-[#2563EB] mb-2 truncate">{{ optional($rating->project)->title ?? $rating->project_title_snapshot ?? 'Proyek diarsipkan' }}</p>
                                        <p class="text-xs text-slate-600 font-medium leading-relaxed">
                                            "{{ $rating->comment ?: 'Pekerjaan yang memuaskan.' }}"
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center bg-white/50 rounded-[2rem] border border-white border-dashed">
                                <div class="w-12 h-12 mx-auto mb-3 rounded-2xl bg-white shadow-sm flex items-center justify-center text-[#2563EB]">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                </div>
                                <h4 class="font-bold text-slate-800 text-sm mb-1">Belum ada ulasan</h4>
                                <p class="text-xs text-slate-500 font-medium">Ulasan dari klien akan muncul di sini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Ratings Modal -->
    <div id="ratings-modal" class="fixed inset-0 z-[140] hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeRatingsModal()"></div>

        <div class="relative min-h-screen flex items-center justify-center px-4 py-8">
            <div class="w-full max-w-2xl rounded-[3rem] bg-white shadow-2xl shadow-[#1E3A8A]/20 overflow-hidden relative">
                <div class="px-8 sm:px-12 pt-12 pb-8 bg-gradient-to-br from-[#EFF6FF] to-white relative">
                    <button type="button" onclick="closeRatingsModal()" class="absolute top-6 right-6 w-12 h-12 rounded-full bg-white border border-slate-100 text-slate-400 hover:text-slate-700 hover:bg-slate-50 transition-all shadow-sm flex items-center justify-center">
                        <span class="sr-only">Tutup</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>

                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-[#2563EB]/10 shadow-sm mb-6">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                        <span class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-600">Reputasi Kualitas</span>
                    </div>

                    <h2 class="font-display text-4xl sm:text-[2.5rem] font-bold text-slate-800 leading-tight mb-4 flex items-center gap-4">
                        <span>{{ $displayRating }}</span>
                        @if(($ratingsCount ?? 0) > 0)
                            <span class="text-xl text-slate-400 font-medium">({{ $ratingsCount }} Ulasan)</span>
                        @endif
                    </h2>
                    <p class="text-slate-500 font-medium leading-relaxed text-sm sm:text-base">
                        Ini adalah rangkuman dari apa yang klien katakan tentang kualitas kerjamu. Pertahankan performa hebat ini!
                    </p>
                </div>

                <div class="px-8 sm:px-12 py-8 max-h-[60vh] overflow-y-auto space-y-5 bg-slate-50">
                    @forelse($recentRatings as $rating)
                        <article class="rounded-[2rem] bg-white p-6 shadow-sm border border-slate-100 hover:border-[#2563EB]/15 hover:shadow-md transition-all">
                            <div class="flex items-start gap-5">
                                <img src="{{ optional($rating->fromUser)->profile_photo ?? 'https://ui-avatars.com/api/?name='.urlencode(optional($rating->fromUser)->name ?? 'UMKM').'&background=random' }}" alt="{{ optional($rating->fromUser)->name ?? 'UMKM' }}" class="w-14 h-14 rounded-full object-cover shrink-0 border-2 border-slate-50">
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3">
                                        <div>
                                            <p class="font-bold text-slate-800 text-lg">{{ optional($rating->fromUser)->name ?? 'UMKM' }}</p>
                                            <p class="text-[10px] font-black uppercase tracking-widest text-[#2563EB] mt-0.5">{{ optional($rating->project)->title ?? $rating->project_title_snapshot ?? 'Proyek diarsipkan' }}</p>
                                        </div>
                                        <p class="text-xs text-slate-400 font-bold">{{ optional($rating->created_at)->diffForHumans() }}</p>
                                    </div>
                                    <div class="flex items-center gap-1 mb-4">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $rating->rating ? 'text-amber-400' : 'text-slate-200' }} fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.388 2.46a1 1 0 00-.364 1.118l1.287 3.97c.3.921-.755 1.688-1.54 1.118l-3.388-2.46a1 1 0 00-1.175 0l-3.388 2.46c-.784.57-1.838-.197-1.539-1.118l1.287-3.97a1 1 0 00-.364-1.118L2.245 9.397c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.97z"/></svg>
                                        @endfor
                                    </div>
                                    <p class="text-sm text-slate-600 leading-relaxed font-medium">
                                        "{{ $rating->comment ?: 'UMKM ini belum menambahkan komentar.' }}"
                                    </p>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-[2.5rem] bg-white border border-dashed border-slate-200 p-12 text-center">
                            <div class="w-16 h-16 rounded-full bg-slate-50 mx-auto mb-4 flex items-center justify-center text-slate-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 1.24 1.24 1.81 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            </div>
                            <h3 class="font-display text-xl font-bold text-slate-800 mb-2">Belum Ada Ulasan</h3>
                            <p class="text-slate-500 font-medium">Begitu UMKM memberi rating setelah proyek selesai, ulasan akan tampil di sini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="py-10 border-t border-slate-200/50 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6 relative z-10">
        <p class="text-slate-400 text-sm font-bold">&copy; 2026 Konekin. Wujudkan ide kreatifmu.</p>
        <div class="flex gap-8">
            <a href="#" class="text-slate-400 hover:text-[#2563EB] text-sm font-bold transition-colors">Bantuan</a>
            <a href="#" class="text-slate-400 hover:text-[#2563EB] text-sm font-bold transition-colors">Privasi</a>
            <a href="#" class="text-slate-400 hover:text-[#2563EB] text-sm font-bold transition-colors">Syarat & Ketentuan</a>
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
