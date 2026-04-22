<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Proyek - Konekin Admin</title>
    
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
        .status-badge {
            @apply px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider;
        }
    </style>
</head>
<body class="antialiased text-[#1E3A8A]">
    
    <!-- Navbar -->
    <x-dashboard-nav-admin />

    <!-- Main Content -->
    <main class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
            <div>
                <h1 class="font-display text-3xl md:text-4xl font-bold text-[#1E3A8A] mb-2">Manajemen Proyek 📁</h1>
                <p class="text-[#1E3A8A]/60 font-medium">Monitor dan moderasi semua proyek yang ada di platform Konekin.</p>
            </div>
            
            <div class="flex gap-3">
                <form action="{{ route('admin.projects') }}" method="GET" class="relative group">
                    <input type="text" name="search" placeholder="Cari proyek..." value="{{ request('search') }}"
                        class="pl-12 pr-6 py-4 bg-white border border-[#2563EB]/10 rounded-[2rem] w-full md:w-80 focus:outline-none focus:ring-4 focus:ring-[#2563EB]/5 focus:border-[#2563EB]/20 transition-all text-sm font-bold shadow-sm group-hover:shadow-md">
                    <div class="absolute left-5 top-1/2 -translate-y-1/2 text-[#2563EB]/40 group-focus-within:text-[#2563EB] transition-colors">
                        <i class="fas fa-search"></i>
                    </div>
                </form>
            </div>
        </div>

        @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 rounded-3xl flex items-center gap-4 text-emerald-700 animate-fade-in">
            <div class="w-10 h-10 rounded-2xl bg-emerald-100 flex items-center justify-center shrink-0">
                <i class="fas fa-check-circle"></i>
            </div>
            <p class="font-bold text-sm">{{ session('success') }}</p>
        </div>
        @endif

        <!-- Projects Table -->
        <div class="bg-white rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-[#2563EB]/5">
                            <th class="px-8 py-6 text-[11px] font-black text-[#1E3A8A]/40 uppercase tracking-[0.2em]">Info Proyek</th>
                            <th class="px-8 py-6 text-[11px] font-black text-[#1E3A8A]/40 uppercase tracking-[0.2em]">Klien / UMKM</th>
                            <th class="px-8 py-6 text-[11px] font-black text-[#1E3A8A]/40 uppercase tracking-[0.2em]">Status</th>
                            <th class="px-8 py-6 text-[11px] font-black text-[#1E3A8A]/40 uppercase tracking-[0.2em]">Budget & Deadline</th>
                            <th class="px-8 py-6 text-[11px] font-black text-[#1E3A8A]/40 uppercase tracking-[0.2em]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#2563EB]/5">
                        @forelse($projects as $project)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-slate-100 overflow-hidden shrink-0 border-2 border-white shadow-sm">
                                        @if($project->thumbnail)
                                            <img src="{{ $project->thumbnail }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-[#2563EB]/20 bg-[#EFF6FF]">
                                                <i class="fas fa-image text-xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-[#1E3A8A] group-hover:text-[#2563EB] transition-colors line-clamp-1">{{ $project->title }}</h4>
                                        <p class="text-xs text-[#1E3A8A]/40 font-bold mt-0.5">{{ $project->category }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-[#EFF6FF] flex items-center justify-center overflow-hidden border border-[#2563EB]/10">
                                        <img src="{{ $project->client_avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($project->client_name).'&background=2563EB&color=fff' }}" class="w-full h-full object-cover">
                                    </div>
                                    <span class="text-sm font-bold text-[#1E3A8A]">{{ $project->client_name }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                @if($project->status === 'open')
                                    <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[10px] font-black uppercase tracking-wider">Mencari Kreator</span>
                                @elseif($project->status === 'hired')
                                    <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-[10px] font-black uppercase tracking-wider">Kreator Terpilih</span>
                                @elseif($project->status === 'in_progress')
                                    <span class="px-3 py-1 bg-purple-50 text-purple-600 rounded-full text-[10px] font-black uppercase tracking-wider">Dikerjakan</span>
                                @elseif($project->status === 'completed')
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black uppercase tracking-wider">Selesai</span>
                                @else
                                    <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-full text-[10px] font-black uppercase tracking-wider">{{ $project->status }}</span>
                                @endif
                            </td>
                            <td class="px-8 py-6">
                                <div class="space-y-1">
                                    <p class="text-sm font-bold text-[#1E3A8A]">Rp {{ number_format($project->budget, 0, ',', '.') }}</p>
                                    <p class="text-[10px] text-[#1E3A8A]/40 font-black uppercase tracking-widest">{{ \Carbon\Carbon::parse($project->deadline)->format('d M Y') }}</p>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('projects.show', $project->id) }}" target="_blank" class="p-2.5 bg-slate-50 text-slate-400 hover:bg-[#2563EB] hover:text-white rounded-xl transition-all" title="Lihat Detail">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <button onclick="confirmDelete('{{ $project->id }}', '{{ $project->title }}')" class="p-2.5 bg-red-50 text-red-400 hover:bg-red-500 hover:text-white rounded-xl transition-all" title="Hapus Proyek">
                                        <i class="fas fa-trash-alt text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center text-slate-200">
                                        <i class="fas fa-folder-open text-4xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-[#1E3A8A] font-bold">Tidak ada proyek ditemukan</p>
                                        <p class="text-sm text-[#1E3A8A]/40 font-medium">Coba ubah kata kunci pencarian Anda.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="fixed inset-0 z-[100] hidden">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeDeleteModal()"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-[3rem] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-white/20">
                    <div class="bg-white px-6 pt-10 pb-8 sm:px-10">
                        <div class="flex flex-col items-center">
                            <div class="w-20 h-20 rounded-3xl bg-red-50 text-red-500 flex items-center justify-center mb-6 text-3xl">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="text-center">
                                <h3 class="text-2xl font-extrabold text-[#1E3A8A] tracking-tight mb-2">Hapus Proyek?</h3>
                                <p class="text-sm text-slate-500 font-medium">Anda akan menghapus proyek <span id="project-name-modal" class="font-bold text-[#1E3A8A]"></span>. Tindakan ini tidak dapat dibatalkan.</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50/80 px-6 py-8 sm:px-10 flex flex-col gap-3">
                        <form id="delete-form" action="" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex justify-center rounded-2xl border border-transparent shadow-xl shadow-red-500/20 px-8 py-4 bg-red-600 text-base font-bold text-white hover:bg-red-700 transition-all">
                                Ya, Hapus Proyek
                            </button>
                        </form>
                        <button type="button" onclick="closeDeleteModal()" class="w-full inline-flex justify-center rounded-2xl border border-slate-200 shadow-sm px-8 py-4 bg-white text-base font-bold text-slate-600 hover:bg-slate-50 transition-all">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id, name) {
            const modal = document.getElementById('delete-modal');
            const form = document.getElementById('delete-form');
            const nameSpan = document.getElementById('project-name-modal');
            
            form.action = `/admin/projects/${id}`;
            nameSpan.innerText = name;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            document.getElementById('delete-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>

</body>
</html>
