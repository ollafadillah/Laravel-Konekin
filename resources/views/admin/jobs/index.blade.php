<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Monitoring Aktivitas Pekerjaan - Konekin Admin</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

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
        
        <!-- Header -->
        <div class="mb-12">
            <h1 class="font-display text-3xl md:text-4xl font-bold text-[#1E3A8A] mb-2">Monitoring Aktivitas 📈</h1>
            <p class="text-[#1E3A8A]/60 font-medium text-lg">Pantau perkembangan pekerjaan dan kolaborasi antar pengguna.</p>
        </div>

        <!-- Activity Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($activeProjects as $project)
            <div class="bg-white rounded-[2.5rem] border border-[#2563EB]/5 p-8 shadow-sm hover:shadow-xl transition-all group relative overflow-hidden">
                <!-- Status Badge -->
                <div class="absolute top-6 right-6">
                    @if($project->status === 'hired')
                        <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-[10px] font-black uppercase tracking-wider">Kreator Terpilih</span>
                    @elseif($project->status === 'in_progress')
                        <span class="px-3 py-1 bg-purple-50 text-purple-600 rounded-full text-[10px] font-black uppercase tracking-wider">Dikerjakan</span>
                    @elseif($project->status === 'completed')
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black uppercase tracking-wider">Selesai</span>
                    @endif
                </div>

                <!-- Content -->
                <div class="flex flex-col h-full">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 overflow-hidden border border-[#2563EB]/5">
                            <img src="{{ $project->thumbnail ?? 'https://ui-avatars.com/api/?name='.urlencode($project->title).'&background=EFF6FF&color=2563EB' }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-[#1E3A8A] line-clamp-1 group-hover:text-[#2563EB] transition-colors">{{ $project->title }}</h3>
                            <p class="text-xs text-[#1E3A8A]/40 font-bold uppercase tracking-widest">{{ $project->category }}</p>
                        </div>
                    </div>

                    <div class="space-y-6 flex-1">
                        <!-- Collaboration Info -->
                        <div class="flex items-center justify-between p-4 bg-slate-50/50 rounded-3xl border border-slate-100">
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-10 h-10 rounded-full border-2 border-white shadow-sm overflow-hidden bg-white">
                                    <img src="{{ $project->client_avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($project->client_name).'&background=2563EB&color=fff' }}" class="w-full h-full object-cover">
                                </div>
                                <span class="text-[10px] font-bold text-[#1E3A8A]/60">{{ Str::limit($project->client_name, 10) }}</span>
                            </div>
                            
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-[2px] bg-[#2563EB]/20 relative">
                                    <div class="absolute -top-1 left-1/2 -translate-x-1/2 text-[#2563EB]/40">
                                        <i class="fas fa-arrow-right text-[10px]"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col items-center gap-2">
                                <div class="w-10 h-10 rounded-full border-2 border-white shadow-sm overflow-hidden bg-white">
                                    <img src="{{ $project->selected_creative_avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($project->selected_creative_name ?? 'Kreator').'&background=7C3AED&color=fff' }}" class="w-full h-full object-cover">
                                </div>
                                <span class="text-[10px] font-bold text-[#1E3A8A]/60">{{ Str::limit($project->selected_creative_name ?? 'Kreator', 10) }}</span>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div>
                            <div class="flex justify-between items-end mb-2">
                                <span class="text-xs font-bold text-[#1E3A8A]/60">Progres Pekerjaan</span>
                                <span class="text-xs font-black text-[#2563EB]">{{ $project->progress_percentage ?? 0 }}%</span>
                            </div>
                            <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-[#2563EB] to-[#0A66C2] rounded-full transition-all duration-500" style="width: {{ $project->progress_percentage ?? 0 }}%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Info -->
                    <div class="mt-8 pt-6 border-t border-slate-50 flex justify-between items-center">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black text-[#1E3A8A]/40 uppercase tracking-[0.2em]">Update Terakhir</span>
                            <span class="text-xs font-bold text-[#1E3A8A]">{{ $project->updated_at->diffForHumans() }}</span>
                        </div>
                        <a href="{{ route('projects.show', $project->id) }}" class="text-xs font-black text-[#2563EB] hover:underline uppercase tracking-widest">Detail</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center bg-white rounded-[3rem] border border-[#2563EB]/5 shadow-sm">
                <div class="w-24 h-24 bg-slate-50 rounded-[2.5rem] flex items-center justify-center text-slate-200 mx-auto mb-6">
                    <i class="fas fa-tasks text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-[#1E3A8A] mb-2">Belum ada aktivitas pekerjaan aktif</h3>
                <p class="text-[#1E3A8A]/40 font-medium">Aktivitas akan muncul setelah UMKM memilih kreator untuk proyek mereka.</p>
            </div>
            @endforelse
        </div>
    </main>

</body>
</html>
