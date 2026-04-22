<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cari Proyek - Konekin</title>
    
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
    <x-dashboard-nav />

    <main class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        
        <!-- Search Header -->
        <div class="mb-12 text-center max-w-3xl mx-auto">
            <h1 class="font-display text-4xl font-bold text-[#1E3A8A] mb-4">Temukan Proyek Impianmu</h1>
            <p class="text-[#1E3A8A]/60 font-medium text-lg mb-8">Ratusan UMKM mencari talenta kreatif sepertimu. Filter berdasarkan minat dan mulai kolaborasi.</p>
            
            <form action="{{ route('projects.index') }}" method="GET" class="relative group">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul proyek atau kata kunci..." class="w-full px-8 py-5 rounded-[2.5rem] bg-white border-2 border-[#2563EB]/5 shadow-xl shadow-[#2563EB]/5 focus:border-[#2563EB] outline-none transition-all font-medium text-[#1E3A8A] pr-32">
                <button type="submit" class="absolute right-3 top-3 bottom-3 px-8 bg-[#2563EB] text-white rounded-full font-bold text-sm hover:bg-[#1E3A8A] transition-all">Cari</button>
            </form>
        </div>

        <!-- Categories / Filters -->
        <div class="flex flex-wrap justify-center gap-3 mb-12">
            @php
                $categories = ['Semua', 'Branding', 'Social Media', 'Web Dev', 'Videography', 'UI/UX Design', 'Illustration'];
                $activeCat = request('category', 'Semua');
            @endphp
            @foreach($categories as $cat)
                <a href="{{ route('projects.index', ['category' => $cat, 'search' => request('search')]) }}" 
                   class="px-6 py-3 rounded-full font-bold text-sm transition-all {{ $activeCat == $cat ? 'bg-[#1E3A8A] text-white shadow-lg shadow-[#1E3A8A]/20' : 'bg-white text-[#1E3A8A]/60 hover:bg-[#EFF6FF] border border-[#2563EB]/5' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>

        <!-- Projects Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8">
            @forelse($projects as $project)
                <div class="bg-white p-8 rounded-[3rem] border border-[#2563EB]/5 shadow-sm hover:shadow-2xl hover:shadow-[#2563EB]/10 transition-all group flex flex-col h-full">
                    <div class="relative h-56 rounded-[2.5rem] overflow-hidden mb-6 bg-slate-100">
                        @if(($project->media_type ?? null) === 'video' && !empty($project->media_url))
                            <video src="{{ $project->media_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" muted playsinline></video>
                        @else
                            <img src="{{ $project->thumbnail }}" alt="{{ $project->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @endif
                        <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                            <span class="px-4 py-2 bg-white/90 backdrop-blur-md text-[#2563EB] text-[10px] font-extrabold uppercase tracking-widest rounded-full shadow-sm">{{ $project->category }}</span>
                            @if(isset($project->created_at) && $project->created_at && $project->created_at->diffInHours(now()) < 24)
                                <span class="px-4 py-2 bg-[#1E3A8A] text-white text-[10px] font-extrabold uppercase tracking-widest rounded-full shadow-sm">Baru</span>
                            @endif
                            @if(!empty($project->media_url))
                                <span class="px-4 py-2 bg-white/90 backdrop-blur-md text-[#1E3A8A] text-[10px] font-extrabold uppercase tracking-widest rounded-full shadow-sm">
                                    {{ ($project->media_type ?? 'image') === 'video' ? 'Video Brief' : 'Foto Brief' }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="flex-grow">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-2xl font-display font-bold text-[#1E3A8A] leading-tight">{{ $project->title }}</h3>
                            <span class="text-[#2563EB] font-display font-bold shrink-0 ml-4 text-xl">Rp {{ $project->budget }}</span>
                        </div>
                        <p class="text-[#1E3A8A]/60 text-sm mb-6 line-clamp-3 font-medium leading-relaxed">{{ $project->description }}</p>

                        <div class="flex flex-wrap gap-3 mb-6">
                            <span class="px-4 py-2 rounded-full bg-[#EFF6FF] text-[#2563EB] text-[10px] font-extrabold uppercase tracking-widest">{{ $project->applications_count ?? 0 }} Apply</span>
                            <span class="px-4 py-2 rounded-full bg-slate-100 text-[#1E3A8A]/70 text-[10px] font-extrabold uppercase tracking-widest">{{ $project->progress_percentage ?? 0 }}% Progress</span>
                            <span class="px-4 py-2 rounded-full bg-white border border-[#2563EB]/10 text-[#1E3A8A]/70 text-[10px] font-extrabold uppercase tracking-widest">{{ $project->status_label ?? strtoupper(str_replace('_', ' ', $project->status ?? 'open')) }}</span>
                        </div>
                        <p class="text-[#1E3A8A]/55 text-xs font-medium mb-3">Deadline: {{ \Illuminate\Support\Carbon::parse($project->deadline)->translatedFormat('d M Y') }}</p>
                        <p class="text-[#2563EB] text-xs font-medium mb-6">{{ $project->progress_summary ?? 'Pantau update proyek dan apply terbaru dari halaman detail.' }}</p>
                    </div>

                    <div class="pt-6 border-t border-[#2563EB]/5 flex items-center justify-between mt-auto">
                        <div class="flex items-center gap-3">
                            <img src="{{ $project->client_avatar }}" class="w-10 h-10 rounded-xl">
                            <div>
                                <p class="text-xs font-bold text-[#1E3A8A]">{{ $project->client_name }}</p>
                                <p class="text-[10px] font-bold text-[#2563EB] uppercase tracking-wider">UMKM Terverifikasi</p>
                            </div>
                        </div>
                        <a href="{{ route('projects.show', $project->id) }}" class="px-8 py-3 bg-[#EFF6FF] text-[#2563EB] hover:bg-[#2563EB] hover:text-white rounded-2xl font-bold text-xs transition-all inline-flex items-center justify-center">Lihat Detail</a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <div class="mb-4 inline-block p-6 bg-white rounded-full shadow-sm">
                        <svg class="w-12 h-12 text-[#1E3A8A]/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-display font-bold text-[#1E3A8A]">Yah, Proyek Tidak Ditemukan</h3>
                    <p class="text-[#1E3A8A]/60 font-medium mt-2">Coba gunakan kata kunci lain atau ubah kategori filternya.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination Placeholder -->
        <div class="mt-16 flex justify-center">
            <nav class="flex items-center gap-2">
                <button class="w-12 h-12 rounded-2xl bg-white border border-[#2563EB]/5 flex items-center justify-center text-[#1E3A8A] hover:bg-[#EFF6FF] transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg></button>
                <button class="w-12 h-12 rounded-2xl bg-[#2563EB] text-white font-bold text-sm shadow-lg shadow-[#2563EB]/20">1</button>
                <button class="w-12 h-12 rounded-2xl bg-white border border-[#2563EB]/5 font-bold text-sm text-[#1E3A8A] hover:bg-[#EFF6FF]">2</button>
                <button class="w-12 h-12 rounded-2xl bg-white border border-[#2563EB]/5 font-bold text-sm text-[#1E3A8A] hover:bg-[#EFF6FF]">3</button>
                <button class="w-12 h-12 rounded-2xl bg-white border border-[#2563EB]/5 flex items-center justify-center text-[#1E3A8A] hover:bg-[#EFF6FF] transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg></button>
            </nav>
        </div>

    </main>

    <!-- Footer Simple -->
    <footer class="py-10 border-t border-[#2563EB]/10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
        <p class="text-[#1E3A8A]/40 text-sm font-bold">&copy; 2026 Konekin. Cari peluang, buat karya.</p>
        <div class="flex gap-8">
            <a href="#" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Bantuan</a>
            <a href="#" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Karir</a>
            <a href="#" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Privasi</a>
        </div>
    </footer>

</body>
</html>
