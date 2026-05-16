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
            background: rgba(37, 99, 235, 0.14); /* Konekin Blue */
            top: -100px; left: -100px;
        }
        .orb-2 {
            width: 400px; height: 400px;
            background: rgba(14, 165, 233, 0.12); /* Sky Blue */
            bottom: 10%; right: -100px;
            animation-delay: -5s;
        }
        .orb-3 {
            width: 350px; height: 350px;
            background: rgba(30, 58, 138, 0.1); /* Professional Navy */
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
            border-color: #2563EB;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.14), inset 0 2px 4px rgba(0, 0, 0, 0.01);
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
            <div class="absolute -left-10 -top-10 w-32 h-32 bg-[#2563EB]/15 rounded-full blur-2xl"></div>
            <div class="relative z-10">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/80 border border-white shadow-sm mb-4">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#2563EB] animate-pulse"></span>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-600">Workspace</span>
                </div>
                <h1 class="font-display text-4xl md:text-5xl font-bold mb-4 tracking-tight text-slate-800">
                    Kawal <span class="bg-gradient-to-r from-[#1E3A8A] via-[#2563EB] to-[#0EA5E9] text-gradient">Karyamu.</span>
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
            <section id="project-{{ $project->id }}" class="glass-bento rounded-[3rem] overflow-hidden smooth-hover hover:shadow-xl hover:shadow-[#1E3A8A]/5 group/card border border-white">
                
                <!-- Project Header Area -->
                <div class="p-8 md:p-12 border-b border-slate-200/50 bg-white/40">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-8">
                        
                        <!-- Project Info -->
                        <div class="flex flex-col sm:flex-row gap-6 lg:gap-8 flex-grow">
                            <div class="w-full sm:w-32 h-48 sm:h-32 rounded-[2rem] overflow-hidden bg-slate-100 shrink-0 shadow-inner relative group-hover/card:shadow-lg group-hover/card:shadow-[#2563EB]/20 smooth-hover">
                                @if(($project->media_type ?? null) === 'video' && !empty($project->media_url))
                                    <video src="{{ $project->media_url }}" class="w-full h-full object-cover group-hover/card:scale-105 smooth-hover" muted playsinline autoplay loop></video>
                                @else
                                    <img src="{{ $project->thumbnail }}" alt="{{ $project->title }}" class="w-full h-full object-cover group-hover/card:scale-105 smooth-hover">
                                @endif
                                <div class="absolute inset-0 bg-[#1E3A8A]/10 opacity-0 group-hover/card:opacity-100 smooth-hover"></div>
                            </div>
                            
                            <div class="flex flex-col justify-center">
                                <div class="flex flex-wrap gap-2 mb-4">
                                    <span class="px-4 py-1.5 rounded-full bg-[#EFF6FF] text-[#2563EB] text-[10px] font-black uppercase tracking-widest border border-[#BFDBFE]/70">{{ $project->category }}</span>
                                    <span class="px-4 py-1.5 rounded-full bg-slate-100 text-slate-500 text-[10px] font-black uppercase tracking-widest border border-slate-200">{{ strtoupper($project->status_label ?? str_replace('_', ' ', $project->status ?? 'open')) }}</span>
                                </div>
                                <h2 class="font-display text-2xl md:text-3xl font-bold text-slate-800 mb-3 group-hover/card:text-[#2563EB] smooth-hover">{{ $project->title }}</h2>
                                <p class="text-sm text-slate-500 font-medium leading-relaxed max-w-3xl line-clamp-2 mb-4">{{ $project->description }}</p>
                                
                                <div class="flex flex-wrap items-center gap-3 text-sm text-slate-500 font-bold">
                                    <div class="flex items-center gap-2 bg-white/60 px-3 py-1.5 rounded-xl border border-white">
                                        <svg class="w-4 h-4 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
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
                            <div class="rounded-[2rem] bg-gradient-to-br from-[#EFF6FF] to-white border border-[#BFDBFE] p-5 text-center shadow-sm hover:shadow-md smooth-hover flex flex-col justify-center relative overflow-hidden">
                                <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-[#60A5FA]/30 rounded-full blur-xl"></div>
                                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-[#2563EB] mb-2 relative z-10">Total Progress</p>
                                <p class="font-display text-2xl md:text-3xl font-bold text-[#2563EB] relative z-10">{{ $project->progress_percentage ?? 0 }}%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Interactive Layout: Form & Timeline -->
                <div class="grid grid-cols-1 xl:grid-cols-[1fr_1.2fr] gap-0">
                    
                    <!-- Left: Form Input -->
                    <div class="p-8 md:p-12 border-b xl:border-b-0 xl:border-r border-slate-200/50 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-[#2563EB]/5 rounded-full blur-3xl -z-10"></div>
                        
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-2xl bg-[#EFF6FF] text-[#2563EB] flex items-center justify-center shrink-0">
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
                                    <span class="text-2xl font-display font-black text-[#2563EB]">{{ $project->progress_percentage ?? 0 }}%</span>
                                </div>
                                
                                <div class="relative w-full h-4 bg-slate-200/80 rounded-full overflow-hidden mb-6 shadow-inner">
                                    <div class="absolute top-0 left-0 h-full bg-gradient-to-r from-[#1E3A8A] via-[#2563EB] to-[#0EA5E9] rounded-full smooth-hover" style="width: {{ $project->progress_percentage ?? 0 }}%">
                                        <div class="w-full h-full bg-white/20 animate-pulse"></div>
                                    </div>
                                </div>

                                <div class="relative">
                                    <select name="progress_percentage" class="appearance-none w-full px-6 py-4 rounded-2xl form-input-premium text-slate-800 font-bold text-lg cursor-pointer">
                                        @foreach([0, 10, 25, 40, 50, 60, 75, 90, 100] as $progress)
                                            <option value="{{ $progress }}" {{ (int) ($project->progress_percentage ?? 0) === $progress ? 'selected' : '' }}>Set ke {{ $progress }}%</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-6 pointer-events-none text-[#2563EB]">
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
                            <div class="bg-white/50 p-6 rounded-[2rem] border border-white shadow-sm border-dashed border-2 hover:border-[#93C5FD] smooth-hover relative group">
                                <label class="block text-sm font-bold text-slate-700 mb-3">Upload Bukti Karya (Opsional)</label>
                                <div class="relative">
                                    <input type="file" name="progress_media" id="progress_media_{{ $project->id }}" accept="image/*,video/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewProgressMedia(this, @js((string) $project->id))">
                                    <div class="flex items-center gap-4 bg-white px-5 py-4 rounded-2xl border border-slate-100 group-hover:border-[#BFDBFE] smooth-hover">
                                        <div class="w-12 h-12 rounded-xl bg-[#EFF6FF] text-[#2563EB] flex items-center justify-center shrink-0">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        </div>
                                        <div>
                                            <p id="progress_file_name_{{ $project->id }}" class="text-sm font-bold text-slate-800">Pilih File Visual</p>
                                            <p id="progress_file_meta_{{ $project->id }}" class="text-xs font-medium text-slate-400 mt-0.5">JPG, PNG, atau MP4 (Maks. 20MB)</p>
                                        </div>
                                    </div>
                                </div>
                                <div id="progress_preview_{{ $project->id }}" class="hidden mt-4 rounded-2xl border border-[#BFDBFE] bg-white p-3 shadow-sm">
                                    <img id="progress_preview_image_{{ $project->id }}" src="" alt="Preview bukti karya" class="hidden w-full max-h-72 rounded-xl object-cover">
                                    <video id="progress_preview_video_{{ $project->id }}" src="" class="hidden w-full max-h-72 rounded-xl object-cover" controls muted playsinline></video>
                                    <div id="progress_preview_file_{{ $project->id }}" class="hidden rounded-xl bg-[#EFF6FF] px-4 py-3 text-sm font-bold text-[#1E3A8A]"></div>
                                </div>
                                @error('progress_media')
                                    <p class="mt-3 ml-2 text-xs font-bold text-red-500 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <button type="submit" class="w-full px-6 py-5 rounded-[2rem] bg-[#1E3A8A] text-white font-bold text-sm md:text-base hover:bg-[#2563EB] transition-all shadow-xl shadow-[#1E3A8A]/20 hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-3">
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
                            <a href="{{ route('projects.show', $project->id) }}" class="flex items-center gap-2 px-5 py-2.5 rounded-full bg-white border border-slate-200 text-slate-600 text-xs font-bold hover:bg-slate-50 hover:text-[#2563EB] transition-all shadow-sm">
                                Detail Proyek
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>

                        <div class="space-y-8 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px before:h-full before:w-0.5 before:bg-gradient-to-b before:from-[#BFDBFE] before:to-transparent">
                            @forelse($project->progress_updates as $update)
                                <div class="relative flex items-start gap-6 group">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-[#2563EB] text-white shadow shrink-0 z-10 smooth-hover group-hover:scale-110 group-hover:shadow-[#2563EB]/30">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0 p-6 rounded-[2rem] bg-white border border-slate-100 shadow-sm smooth-hover group-hover:shadow-md group-hover:border-[#BFDBFE]">
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="px-3 py-1 bg-[#EFF6FF] text-[#2563EB] rounded-full text-[10px] font-black uppercase tracking-widest border border-[#BFDBFE]/70">{{ $update->progress_percentage }}% Selesai</span>
                                            <span class="text-[10px] font-bold text-slate-400">{{ optional($update->created_at)->diffForHumans() ?? 'Baru saja' }}</span>
                                        </div>
                                        
                                        <div class="text-sm font-medium text-slate-600 leading-relaxed break-words" style="word-break: break-word;">
                                            @php
                                                $isLongText = strlen($update->note) > 150;
                                            @endphp
                                            @if($isLongText)
                                                <div x-data="{ expanded: false }">
                                                    <p :class="expanded ? '' : 'line-clamp-3'" class="break-words">{{ $update->note }}</p>
                                                    <button @click="expanded = !expanded" class="text-[#2563EB] hover:text-[#1E3A8A] text-xs font-bold mt-2 transition-colors flex items-center gap-1">
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
                                                <a href="{{ $update->media_url }}" target="_blank" class="inline-flex items-center justify-center w-full gap-2 px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 text-[11px] font-bold hover:bg-[#2563EB] hover:text-white hover:border-[#2563EB] smooth-hover">
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
                                        <div class="w-14 h-14 mx-auto bg-[#EFF6FF] rounded-full flex items-center justify-center mb-4 text-[#2563EB]">
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
            <div class="glass-bento rounded-[3rem] border border-white shadow-xl shadow-[#1E3A8A]/5 p-16 text-center max-w-2xl mx-auto mt-10">
                <div class="w-24 h-24 mx-auto mb-8 bg-white rounded-full shadow-sm flex items-center justify-center relative">
                    <div class="absolute inset-0 bg-[#2563EB]/15 rounded-full blur-xl animate-pulse"></div>
                    <svg class="w-12 h-12 text-[#2563EB] relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <h2 class="font-display text-3xl md:text-4xl font-bold text-slate-800 mb-4">Belum Ada Proyek Aktif</h2>
                <p class="text-slate-500 font-medium mb-10 text-lg">Kamu belum memiliki proyek yang sedang dikerjakan. Cari dan lamar proyek UMKM yang cocok dengan skill emasmu.</p>
                <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-3 px-8 py-4 rounded-full bg-[#1E3A8A] text-white font-bold hover:bg-[#2563EB] hover:scale-105 transition-all shadow-xl shadow-[#1E3A8A]/20">
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
                <a href="#" class="text-slate-400 hover:text-[#2563EB] text-sm font-bold transition-colors">Pusat Bantuan</a>
                <a href="#" class="text-slate-400 hover:text-[#2563EB] text-sm font-bold transition-colors">Privasi</a>
            </div>
        </div>
    </footer>

    <script>
        function formatFileSize(bytes) {
            if (!bytes) {
                return '0 KB';
            }

            const units = ['B', 'KB', 'MB', 'GB'];
            const index = Math.min(Math.floor(Math.log(bytes) / Math.log(1024)), units.length - 1);
            return `${(bytes / Math.pow(1024, index)).toFixed(index === 0 ? 0 : 1)} ${units[index]}`;
        }

        function previewProgressMedia(input, projectId) {
            const file = input.files && input.files[0];
            const preview = document.getElementById(`progress_preview_${projectId}`);
            const image = document.getElementById(`progress_preview_image_${projectId}`);
            const video = document.getElementById(`progress_preview_video_${projectId}`);
            const fallback = document.getElementById(`progress_preview_file_${projectId}`);
            const fileName = document.getElementById(`progress_file_name_${projectId}`);
            const fileMeta = document.getElementById(`progress_file_meta_${projectId}`);

            [image, video, fallback].forEach((element) => {
                element.classList.add('hidden');
            });

            if (!file) {
                preview.classList.add('hidden');
                fileName.textContent = 'Pilih File Visual';
                fileMeta.textContent = 'JPG, PNG, atau MP4 (Maks. 20MB)';
                return;
            }

            const objectUrl = URL.createObjectURL(file);
            preview.classList.remove('hidden');
            fileName.textContent = file.name;
            fileMeta.textContent = `${file.type || 'File'} - ${formatFileSize(file.size)}`;

            if (file.type.startsWith('image/')) {
                image.src = objectUrl;
                image.classList.remove('hidden');
                return;
            }

            if (file.type.startsWith('video/')) {
                video.src = objectUrl;
                video.classList.remove('hidden');
                return;
            }

            fallback.textContent = `${file.name} siap diupload`;
            fallback.classList.remove('hidden');
        }
    </script>
</body>
</html>
