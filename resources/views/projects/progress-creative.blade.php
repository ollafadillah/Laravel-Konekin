<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Progress Proyek Creative Worker - Konekin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
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
        .text-gradient {
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        /* Animated Background Orbs */
        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            z-index: -1;
            animation: float 20s infinite ease-in-out alternate;
        }
        .orb-1 {
            width: 500px; height: 500px;
            background: rgba(99, 102, 241, 0.15); /* Indigo */
            top: -100px; left: -100px;
        }
        .orb-2 {
            width: 400px; height: 400px;
            background: rgba(236, 72, 153, 0.12); /* Pink */
            bottom: 10%; right: -100px;
            animation-delay: -5s;
        }
        .orb-3 {
            width: 350px; height: 350px;
            background: rgba(16, 185, 129, 0.1); /* Emerald */
            top: 30%; left: 40%;
            animation-delay: -10s;
        }
        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(40px, 40px) scale(1.1); }
            100% { transform: translate(-40px, 20px) scale(0.9); }
        }
        
        .smooth-hover {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .form-input-premium {
            background: rgba(255, 255, 255, 0.7);
            border: 2px solid rgba(255, 255, 255, 0.9);
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.02);
            transition: all 0.3s ease;
        }
        .form-input-premium:focus {
            background: #fff;
            border-color: #818cf8;
            box-shadow: 0 0 0 4px rgba(129, 140, 248, 0.15), inset 0 2px 4px rgba(0, 0, 0, 0.01);
            outline: none;
        }
    </style>
</head>
<body class="antialiased text-[#1E293B] relative min-h-screen flex flex-col">
    
    <!-- Background Orbs -->
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <x-dashboard-nav />

    <main class="pt-32 pb-24 px-4 sm:px-6 lg:px-8 max-w-[85rem] mx-auto space-y-10 flex-grow relative z-10 w-full">
        
        <!-- Header -->
        <div class="max-w-4xl relative">
            <div class="absolute -left-10 -top-10 w-32 h-32 bg-indigo-400/20 rounded-full blur-2xl"></div>
            <div class="relative z-10">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/80 border border-white shadow-sm mb-4">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-500 animate-pulse"></span>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-600">Workspace</span>
                </div>
                <h1 class="font-display text-4xl md:text-5xl font-bold mb-4 tracking-tight text-slate-800">
                    Kawal <span class="bg-gradient-to-r from-indigo-600 to-purple-600 text-gradient">Karyamu.</span>
                </h1>
                <p class="text-slate-500 font-medium text-lg max-w-2xl leading-relaxed">
                    Lihat proyek yang sedang aktif, laporkan progres kepada klien, dan kirimkan mahakaryamu yang luar biasa.
                </p>
            </div>
        </div>

        @if(session('success'))
            <div class="p-5 bg-emerald-50 border border-emerald-100 rounded-3xl flex items-center gap-4 shadow-sm animate-fade-in">
                <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center shrink-0 text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <p class="text-emerald-800 font-bold">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="p-5 bg-red-50 border border-red-100 rounded-3xl flex items-center gap-4 shadow-sm animate-fade-in">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0 text-red-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <p class="text-red-800 font-bold">{{ session('error') }}</p>
            </div>
        @endif

        @forelse($projects as $project)
            <section id="project-{{ $project->id }}" class="glass-bento rounded-[3rem] overflow-hidden smooth-hover hover:shadow-xl hover:shadow-indigo-900/5 group/card border border-white">
                
                <!-- Project Header Area -->
                <div class="p-8 md:p-12 border-b border-slate-200/50 bg-white/40">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-8">
                        
                        <!-- Project Info -->
                        <div class="flex flex-col sm:flex-row gap-6 lg:gap-8 flex-grow">
                            <div class="w-full sm:w-32 h-48 sm:h-32 rounded-[2rem] overflow-hidden bg-slate-100 shrink-0 shadow-inner relative group-hover/card:shadow-lg group-hover/card:shadow-indigo-500/20 smooth-hover">
                                @if(($project->media_type ?? null) === 'video' && !empty($project->media_url))
                                    <video src="{{ $project->media_url }}" class="w-full h-full object-cover group-hover/card:scale-105 smooth-hover" muted playsinline autoplay loop></video>
                                @else
                                    <img src="{{ $project->thumbnail }}" alt="{{ $project->title }}" class="w-full h-full object-cover group-hover/card:scale-105 smooth-hover">
                                @endif
                                <div class="absolute inset-0 bg-indigo-900/10 opacity-0 group-hover/card:opacity-100 smooth-hover"></div>
                            </div>
                            
                            <div class="flex flex-col justify-center">
                                <div class="flex flex-wrap gap-2 mb-4">
                                    <span class="px-4 py-1.5 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-widest border border-indigo-100/50">{{ $project->category }}</span>
                                    <span class="px-4 py-1.5 rounded-full bg-slate-100 text-slate-500 text-[10px] font-black uppercase tracking-widest border border-slate-200">{{ strtoupper($project->status_label ?? str_replace('_', ' ', $project->status ?? 'open')) }}</span>
                                </div>
                                <h2 class="font-display text-2xl md:text-3xl font-bold text-slate-800 mb-3 group-hover/card:text-indigo-600 smooth-hover">{{ $project->title }}</h2>
                                <p class="text-sm text-slate-500 font-medium leading-relaxed max-w-3xl line-clamp-2 mb-4">{{ $project->description }}</p>
                                
                                <div class="flex flex-wrap items-center gap-3 text-sm text-slate-500 font-bold">
                                    <div class="flex items-center gap-2 bg-white/60 px-3 py-1.5 rounded-xl border border-white">
                                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        {{ $project->client_name }}
                                    </div>
                                    <div class="flex items-center gap-2 bg-white/60 px-3 py-1.5 rounded-xl border border-white">
                                        <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"/></svg>
                                        {{ \Illuminate\Support\Carbon::parse($project->deadline)->translatedFormat('d M Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Stats Cards -->
                        <div class="grid grid-cols-2 gap-4 lg:w-[300px] shrink-0">
                            <div class="rounded-[2rem] bg-white border border-slate-100 p-5 text-center shadow-sm hover:shadow-md smooth-hover flex flex-col justify-center">
                                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2">Budget Klien</p>
                                <p class="font-display text-xl md:text-2xl font-bold text-slate-800">Rp {{ $project->budget }}</p>
                            </div>
                            <div class="rounded-[2rem] bg-gradient-to-br from-indigo-50 to-white border border-indigo-100 p-5 text-center shadow-sm hover:shadow-md smooth-hover flex flex-col justify-center relative overflow-hidden">
                                <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-indigo-200/40 rounded-full blur-xl"></div>
                                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-400 mb-2 relative z-10">Total Progress</p>
                                <p class="font-display text-2xl md:text-3xl font-bold text-indigo-600 relative z-10">{{ $project->progress_percentage ?? 0 }}%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Interactive Layout: Form & Timeline -->
                <div class="grid grid-cols-1 xl:grid-cols-[1fr_1.2fr] gap-0">
                    
                    <!-- Left: Form Input -->
                    <div class="p-8 md:p-12 border-b xl:border-b-0 xl:border-r border-slate-200/50 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-400/5 rounded-full blur-3xl -z-10"></div>
                        
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            </div>
                            <div>
                                <h3 class="font-display text-xl font-bold text-slate-800">Kirim Update</h3>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-0.5">Beritahu Perkembanganmu</p>
                            </div>
                        </div>
                        
                        <form action="{{ route('projects.progress.creative.update', $project->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            
                            <!-- Progress Slider / Select -->
                            <div class="bg-white/50 p-6 rounded-[2rem] border border-white shadow-sm">
                                <div class="flex justify-between items-end mb-4">
                                    <label class="text-sm font-bold text-slate-700">Persentase Saat Ini</label>
                                    <span class="text-2xl font-display font-black text-indigo-600">{{ $project->progress_percentage ?? 0 }}%</span>
                                </div>
                                
                                <div class="relative w-full h-4 bg-slate-200/80 rounded-full overflow-hidden mb-6 shadow-inner">
                                    <div class="absolute top-0 left-0 h-full bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full smooth-hover" style="width: {{ $project->progress_percentage ?? 0 }}%">
                                        <div class="w-full h-full bg-white/20 animate-pulse"></div>
                                    </div>
                                </div>

                                <div class="relative">
                                    <select name="progress_percentage" class="appearance-none w-full px-6 py-4 rounded-2xl form-input-premium text-slate-800 font-bold text-lg cursor-pointer">
                                        @foreach([0, 10, 25, 40, 50, 60, 75, 90, 100] as $progress)
                                            <option value="{{ $progress }}" {{ (int) ($project->progress_percentage ?? 0) === $progress ? 'selected' : '' }}>Set ke {{ $progress }}%</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-6 pointer-events-none text-indigo-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Note -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 ml-2 mb-2">Catatan Pengerjaan</label>
                                <textarea name="note" rows="4" class="w-full px-6 py-4 rounded-[2rem] form-input-premium text-slate-800 placeholder-slate-400 font-medium resize-none" placeholder="Ceritakan detail apa saja yang baru kamu selesaikan... (Contoh: Selesai mewarnai karakter utama)">{{ old('note') }}</textarea>
                                @error('note')
                                    <p class="mt-2 ml-2 text-xs font-bold text-red-500 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- File Upload -->
                            <div class="bg-white/50 p-6 rounded-[2rem] border border-white shadow-sm border-dashed border-2 hover:border-indigo-300 smooth-hover relative group">
                                <label class="block text-sm font-bold text-slate-700 mb-3">Upload Bukti Karya (Opsional)</label>
                                <div class="relative">
                                    <input type="file" name="progress_media" accept="image/*,video/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                    <div class="flex items-center gap-4 bg-white px-5 py-4 rounded-2xl border border-slate-100 group-hover:border-indigo-200 smooth-hover">
                                        <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-500 flex items-center justify-center shrink-0">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-800">Pilih File Visual</p>
                                            <p class="text-xs font-medium text-slate-400 mt-0.5">JPG, PNG, atau MP4 (Maks. 20MB)</p>
                                        </div>
                                    </div>
                                </div>
                                @error('progress_media')
                                    <p class="mt-3 ml-2 text-xs font-bold text-red-500 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <button type="submit" class="w-full px-6 py-5 rounded-[2rem] bg-slate-900 text-white font-bold text-sm md:text-base hover:bg-black transition-all shadow-xl shadow-slate-900/20 hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-3">
                                <span>Simpan & Kirim Update</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </form>
                    </div>

                    <!-- Right: Timeline -->
                    <div class="p-8 md:p-12 bg-white/30 backdrop-blur-md">
                        <div class="flex items-center justify-between gap-4 mb-8">
                            <div>
                                <h3 class="font-display text-xl font-bold text-slate-800">Riwayat Perjalanan</h3>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-0.5">Jejak Karya</p>
                            </div>
                            <a href="{{ route('projects.show', $project->id) }}" class="flex items-center gap-2 px-5 py-2.5 rounded-full bg-white border border-slate-200 text-slate-600 text-xs font-bold hover:bg-slate-50 hover:text-indigo-600 transition-all shadow-sm">
                                Detail Proyek
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>

                        <div class="space-y-8 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px before:h-full before:w-0.5 before:bg-gradient-to-b before:from-indigo-200 before:to-transparent">
                            @forelse($project->progress_updates as $update)
                                <div class="relative flex items-start gap-6 group">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-indigo-500 text-white shadow shrink-0 z-10 smooth-hover group-hover:scale-110 group-hover:shadow-indigo-500/30">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0 p-6 rounded-[2rem] bg-white border border-slate-100 shadow-sm smooth-hover group-hover:shadow-md group-hover:border-indigo-100">
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-indigo-100/50">{{ $update->progress_percentage }}% Selesai</span>
                                            <span class="text-[10px] font-bold text-slate-400">{{ optional($update->created_at)->diffForHumans() ?? 'Baru saja' }}</span>
                                        </div>
                                        
                                        <div class="text-sm font-medium text-slate-600 leading-relaxed break-words" style="word-break: break-word;">
                                            @php
                                                $isLongText = strlen($update->note) > 150;
                                            @endphp
                                            @if($isLongText)
                                                <div x-data="{ expanded: false }">
                                                    <p :class="expanded ? '' : 'line-clamp-3'" class="break-words">{{ $update->note }}</p>
                                                    <button @click="expanded = !expanded" class="text-indigo-500 hover:text-indigo-700 text-xs font-bold mt-2 transition-colors flex items-center gap-1">
                                                        <span x-text="expanded ? 'Tutup' : 'Lihat Selengkapnya'"></span>
                                                        <svg class="w-3 h-3 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                                    </button>
                                                </div>
                                            @else
                                                <p class="break-words">{{ $update->note }}</p>
                                            @endif
                                        </div>
                                        
                                        @if(!empty($update->media_url))
                                            <div class="mt-4 pt-4 border-t border-slate-50">
                                                <a href="{{ $update->media_url }}" target="_blank" class="inline-flex items-center justify-center w-full gap-2 px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 text-[11px] font-bold hover:bg-indigo-500 hover:text-white hover:border-indigo-500 smooth-hover">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        @if(($update->media_type ?? 'image') === 'video')
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        @else
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2-2v12a2 2 0 002 2z"/>
                                                        @endif
                                                    </svg>
                                                    Lihat Visual Progress
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="relative flex items-center justify-center py-10 w-full z-10">
                                    <div class="bg-white/80 backdrop-blur border border-dashed border-slate-300 rounded-[2rem] p-8 text-center max-w-sm mx-auto shadow-sm">
                                        <div class="w-14 h-14 mx-auto bg-indigo-50 rounded-full flex items-center justify-center mb-4 text-indigo-400">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <h4 class="font-bold text-slate-800 mb-1">Riwayat Kosong</h4>
                                        <p class="text-xs font-medium text-slate-500">Kirim update progress pertamamu di sebelah kiri, dan jejaknya akan tercatat di sini.</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </section>
        @empty
            <div class="glass-bento rounded-[3rem] border border-white shadow-xl shadow-indigo-900/5 p-16 text-center max-w-2xl mx-auto mt-10">
                <div class="w-24 h-24 mx-auto mb-8 bg-white rounded-full shadow-sm flex items-center justify-center relative">
                    <div class="absolute inset-0 bg-indigo-400/20 rounded-full blur-xl animate-pulse"></div>
                    <svg class="w-12 h-12 text-indigo-500 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <h2 class="font-display text-3xl md:text-4xl font-bold text-slate-800 mb-4">Belum Ada Proyek Aktif</h2>
                <p class="text-slate-500 font-medium mb-10 text-lg">Kamu belum memiliki proyek yang sedang dikerjakan. Cari dan lamar proyek UMKM yang cocok dengan skill emasmu.</p>
                <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-3 px-8 py-4 rounded-full bg-slate-900 text-white font-bold hover:bg-black hover:scale-105 transition-all shadow-xl shadow-slate-900/20">
                    Mulai Eksplorasi Proyek
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        @endforelse
    </main>

    <!-- Footer -->
    <footer class="py-10 border-t border-slate-200/50 mt-auto relative z-10 bg-white/30 backdrop-blur-md">
        <div class="max-w-[85rem] mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <p class="text-slate-400 text-sm font-bold">&copy; 2026 Konekin. Elevate your craft.</p>
            <div class="flex gap-8">
                <a href="#" class="text-slate-400 hover:text-indigo-600 text-sm font-bold transition-colors">Pusat Bantuan</a>
                <a href="#" class="text-slate-400 hover:text-indigo-600 text-sm font-bold transition-colors">Privasi</a>
            </div>
        </div>
    </footer>
</body>
</html>
