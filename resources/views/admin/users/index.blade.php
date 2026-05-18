<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen User - Konekin Admin</title>
    
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
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
            <div>
                <h1 class="font-display text-3xl md:text-4xl font-bold text-[#1E3A8A] mb-2">Manajemen User 👥</h1>
                <p class="text-[#1E3A8A]/60 font-medium">Pantau dan kelola seluruh pengguna dalam ekosistem Konekin.</p>
            </div>
            
        </div>

        @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center gap-3 animate-fade-in">
            <i class="fas fa-check-circle"></i>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
        @endif

        <form action="{{ route('admin.users') }}" method="GET" class="mb-8 bg-white rounded-[2rem] border border-[#2563EB]/5 shadow-sm p-5">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                <div class="lg:col-span-4">
                    <label for="search" class="block text-[10px] font-black uppercase tracking-widest text-[#1E3A8A]/40 mb-2">Cari User</label>
                    <div class="relative">
                        <input type="search" name="search" id="search" value="{{ $search }}" placeholder="Nama, email, atau kota"
                            class="w-full rounded-2xl border border-[#2563EB]/10 bg-[#F8FAFC] px-5 py-3 pl-11 text-sm font-semibold text-[#1E3A8A] outline-none transition focus:border-[#2563EB] focus:bg-white focus:ring-4 focus:ring-[#2563EB]/10">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-[#1E3A8A]/30"></i>
                    </div>
                </div>
                <div class="lg:col-span-2">
                    <label for="type" class="block text-[10px] font-black uppercase tracking-widest text-[#1E3A8A]/40 mb-2">Tipe</label>
                    <select name="type" id="type" class="w-full rounded-2xl border border-[#2563EB]/10 bg-[#F8FAFC] px-4 py-3 text-sm font-bold text-[#1E3A8A] outline-none transition focus:border-[#2563EB] focus:bg-white focus:ring-4 focus:ring-[#2563EB]/10">
                        <option value="">Semua</option>
                        <option value="creative_worker" @selected($type === 'creative_worker')>Creative</option>
                        <option value="umkm" @selected($type === 'umkm')>UMKM</option>
                    </select>
                </div>
                <div class="lg:col-span-3">
                    <label for="sort" class="block text-[10px] font-black uppercase tracking-widest text-[#1E3A8A]/40 mb-2">Urutkan</label>
                    <select name="sort" id="sort" class="w-full rounded-2xl border border-[#2563EB]/10 bg-[#F8FAFC] px-4 py-3 text-sm font-bold text-[#1E3A8A] outline-none transition focus:border-[#2563EB] focus:bg-white focus:ring-4 focus:ring-[#2563EB]/10">
                        <option value="joined_newest" @selected($sort === 'joined_newest')>Tanggal join terbaru</option>
                        <option value="joined_oldest" @selected($sort === 'joined_oldest')>Tanggal join terlama</option>
                        <option value="name_asc" @selected($sort === 'name_asc')>Nama A-Z</option>
                        <option value="name_desc" @selected($sort === 'name_desc')>Nama Z-A</option>
                    </select>
                </div>
                <div class="lg:col-span-1">
                    <label for="per_page" class="block text-[10px] font-black uppercase tracking-widest text-[#1E3A8A]/40 mb-2">List</label>
                    <select name="per_page" id="per_page" class="w-full rounded-2xl border border-[#2563EB]/10 bg-[#F8FAFC] px-4 py-3 text-sm font-bold text-[#1E3A8A] outline-none transition focus:border-[#2563EB] focus:bg-white focus:ring-4 focus:ring-[#2563EB]/10">
                        @foreach([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}" @selected($perPage === $size)>{{ $size }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="lg:col-span-2 flex items-end gap-2">
                    <button type="submit" class="flex-1 rounded-2xl bg-[#1E3A8A] px-5 py-3 text-sm font-bold text-white shadow-lg shadow-[#1E3A8A]/10 transition hover:bg-[#2563EB]">
                        Terapkan
                    </button>
                    <a href="{{ route('admin.users') }}" class="rounded-2xl bg-slate-100 px-4 py-3 text-sm font-bold text-[#1E3A8A]/60 transition hover:bg-slate-200" title="Reset filter">
                        <i class="fas fa-rotate-left"></i>
                    </a>
                </div>
            </div>
            <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-xs font-bold text-[#1E3A8A]/45">
                <span>Menampilkan {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} user.</span>
                @if($search !== '')
                    <span>Query aktif: <span class="text-[#2563EB]">{{ $search }}</span></span>
                @endif
            </div>
        </form>

        <!-- Users Table Card -->
        <div class="bg-white rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#F8FAFC] border-b border-[#2563EB]/5">
                            <th class="px-8 py-6 text-xs font-extrabold uppercase tracking-widest text-[#1E3A8A]/40">User</th>
                            <th class="px-8 py-6 text-xs font-extrabold uppercase tracking-widest text-[#1E3A8A]/40">Tipe</th>
                            <th class="px-8 py-6 text-xs font-extrabold uppercase tracking-widest text-[#1E3A8A]/40">Join</th>
                            <th class="px-8 py-6 text-xs font-extrabold uppercase tracking-widest text-[#1E3A8A]/40">Status</th>
                            <th class="px-8 py-6 text-xs font-extrabold uppercase tracking-widest text-[#1E3A8A]/40">Peringatan</th>
                            <th class="px-8 py-6 text-xs font-extrabold uppercase tracking-widest text-[#1E3A8A]/40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#2563EB]/5">
                        @forelse($users as $u)
                        <tr class="hover:bg-[#F8FAFC]/50 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <img src="{{ $u->profile_photo ?? 'https://ui-avatars.com/api/?name='.urlencode($u->name).'&background=random' }}" class="w-12 h-12 rounded-2xl object-cover shadow-sm">
                                    <div>
                                        <p class="font-bold text-[#1E3A8A]">{{ $u->name }}</p>
                                        <p class="text-xs text-[#1E3A8A]/40 font-bold">{{ $u->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1.5 {{ $u->type === 'umkm' ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600' }} text-[10px] font-extrabold uppercase tracking-widest rounded-full">
                                    {{ $u->type === 'umkm' ? 'UMKM' : 'Creative' }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-sm font-bold text-[#1E3A8A]">{{ optional($u->created_at)->translatedFormat('d M Y') ?? '-' }}</p>
                                <p class="text-[10px] font-bold text-[#1E3A8A]/35">{{ optional($u->created_at)->format('H:i') ?? '' }}</p>
                            </td>
                            <td class="px-8 py-6">
                                @if($u->status === 'active' || !$u->status)
                                    <span class="flex items-center gap-1.5 text-emerald-600 text-xs font-bold">
                                        <span class="w-1.5 h-1.5 bg-emerald-600 rounded-full"></span> Aktif
                                    </span>
                                @elseif($u->status === 'warned')
                                    <span class="flex items-center gap-1.5 text-amber-600 text-xs font-bold">
                                        <span class="w-1.5 h-1.5 bg-amber-600 rounded-full"></span> Peringatan
                                    </span>
                                @elseif($u->status === 'suspended')
                                    <span class="flex items-center gap-1.5 text-red-600 text-xs font-bold">
                                        <span class="w-1.5 h-1.5 bg-red-600 rounded-full"></span> Ditangguhkan
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-sm font-bold {{ count($u->warnings ?? []) > 0 ? 'text-amber-600' : 'text-[#1E3A8A]/20' }}">
                                    {{ count($u->warnings ?? []) }} SP
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-2">
                                    <!-- Warn Button -->
                                    <button onclick="openWarnModal(@js((string) $u->id), @js($u->name))" class="group flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 shadow-sm ring-1 ring-amber-100 transition-all hover:bg-amber-100 hover:shadow-md" title="Beri Peringatan" aria-label="Beri peringatan">
                                        <img src="{{ asset('images/assets/warning.png') }}" alt="" class="h-6 w-6 object-contain transition-transform group-hover:scale-110">
                                    </button>
                                    
                                    <!-- Suspend/Activate Toggle -->
                                    @if($u->status === 'suspended')
                                        <form action="{{ route('admin.users.activate', $u->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 shadow-sm ring-1 ring-emerald-100 transition-all hover:bg-emerald-600 hover:text-white hover:shadow-md" title="Aktifkan Kembali" aria-label="Aktifkan kembali">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.users.suspend', $u->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menangguhkan akun ini?')">
                                            @csrf
                                            <button type="submit" class="group flex h-11 w-11 items-center justify-center rounded-xl bg-red-50 shadow-sm ring-1 ring-red-100 transition-all hover:bg-red-100 hover:shadow-md" title="Tangguhkan Akun" aria-label="Tangguhkan akun">
                                                <img src="{{ asset('images/assets/delete.png') }}" alt="" class="h-6 w-6 object-contain transition-transform group-hover:scale-110">
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-8 py-16 text-center">
                                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-50 text-slate-300">
                                    <i class="fas fa-user-slash text-2xl"></i>
                                </div>
                                <p class="font-display text-xl font-bold text-[#1E3A8A]">User tidak ditemukan</p>
                                <p class="mt-2 text-sm font-medium text-[#1E3A8A]/50">Coba ubah keyword, tipe user, atau reset filter.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
                <div class="border-t border-slate-200 bg-slate-100 px-6 py-5">
                    @php
                        $currentPage = $users->currentPage();
                        $lastPage = $users->lastPage();
                        $pages = collect([1, $lastPage, $currentPage - 1, $currentPage, $currentPage + 1])
                            ->filter(fn ($page) => $page >= 1 && $page <= $lastPage)
                            ->unique()
                            ->sort()
                            ->values();
                    @endphp

                    <nav class="flex items-center justify-center" aria-label="Pagination">
                        <div class="inline-flex overflow-hidden rounded-lg border border-slate-300 bg-white shadow-sm">
                            @if($users->onFirstPage())
                                <span class="flex h-11 min-w-12 items-center justify-center border-r border-slate-300 bg-slate-200 px-4 text-slate-400">
                                    <i class="fas fa-chevron-left text-sm"></i>
                                </span>
                            @else
                                <a href="{{ $users->previousPageUrl() }}" class="flex h-11 min-w-12 items-center justify-center border-r border-slate-300 bg-slate-200 px-4 text-slate-600 transition hover:bg-slate-300 hover:text-slate-800" aria-label="Halaman sebelumnya">
                                    <i class="fas fa-chevron-left text-sm"></i>
                                </a>
                            @endif

                            @foreach($pages as $index => $page)
                                @if($index > 0 && $page - $pages[$index - 1] > 1)
                                    <span class="flex h-11 min-w-12 items-center justify-center border-r border-slate-300 bg-slate-100 px-4 text-sm font-bold text-slate-400">...</span>
                                @endif

                                @if($page === $currentPage)
                                    <span class="flex h-11 min-w-12 items-center justify-center border-r border-slate-300 bg-slate-600 px-4 text-sm font-bold text-white" aria-current="page">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $users->url($page) }}" class="flex h-11 min-w-12 items-center justify-center border-r border-slate-300 bg-slate-200 px-4 text-sm font-bold text-slate-600 transition hover:bg-slate-300 hover:text-slate-800" aria-label="Ke halaman {{ $page }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach

                            @if($users->hasMorePages())
                                <a href="{{ $users->nextPageUrl() }}" class="flex h-11 min-w-12 items-center justify-center bg-slate-200 px-4 text-slate-600 transition hover:bg-slate-300 hover:text-slate-800" aria-label="Halaman berikutnya">
                                    <i class="fas fa-chevron-right text-sm"></i>
                                </a>
                            @else
                                <span class="flex h-11 min-w-12 items-center justify-center bg-slate-200 px-4 text-slate-400">
                                    <i class="fas fa-chevron-right text-sm"></i>
                                </span>
                            @endif
                        </div>
                    </nav>
                </div>
            @endif
        </div>
    </main>

    <!-- Warn Modal -->
    <div id="warn-modal" class="fixed inset-0 z-[100] hidden">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeWarnModal()"></div>
        <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
            <div class="bg-white rounded-[2.5rem] w-full max-w-lg shadow-2xl overflow-hidden border border-white/20">
                <div class="bg-gradient-to-r from-amber-500 to-orange-600 p-8 text-white relative">
                    <div class="relative z-10">
                        <h3 class="text-2xl font-display font-bold mb-1">Kirim Surat Peringatan</h3>
                        <p class="text-amber-100 text-sm">Berikan alasan pelanggaran untuk user <span id="warn-user-name" class="font-bold underline"></span></p>
                    </div>
                </div>
                <form id="warn-form" method="POST" action="" class="p-8 space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-[#1E3A8A] mb-3">Alasan Pelanggaran</label>
                        <textarea name="reason" rows="4" class="w-full px-5 py-4 rounded-2xl border-2 border-[#2563EB]/10 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition-all outline-none" placeholder="Tuliskan detail pelanggaran di sini..." required></textarea>
                    </div>
                    <div class="flex gap-4">
                        <button type="button" onclick="closeWarnModal()" class="flex-1 py-4 px-6 font-bold text-[#1E3A8A]/60 bg-slate-100 rounded-2xl hover:bg-slate-200 transition-all">Batal</button>
                        <button type="submit" class="flex-2 py-4 px-10 font-bold text-white bg-amber-600 rounded-2xl hover:bg-amber-700 shadow-lg shadow-amber-600/20 transition-all">Kirim SP</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openWarnModal(userId, userName) {
            document.getElementById('warn-user-name').innerText = userName;
            document.getElementById('warn-form').action = "/admin/users/" + userId + "/warn";
            document.getElementById('warn-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeWarnModal() {
            document.getElementById('warn-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>

</body>
</html>
