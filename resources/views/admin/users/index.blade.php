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
            
            <!-- Filters -->
            <div class="flex bg-white p-1.5 rounded-2xl border border-[#2563EB]/10 shadow-sm">
                <a href="{{ route('admin.users') }}" class="px-5 py-2.5 {{ !$type ? 'bg-[#2563EB] text-white shadow-md' : 'text-[#1E3A8A]/60 hover:bg-[#EFF6FF]' }} rounded-xl text-sm font-bold transition-all">Semua</a>
                <a href="{{ route('admin.users', ['type' => 'creative_worker']) }}" class="px-5 py-2.5 {{ $type === 'creative_worker' ? 'bg-[#2563EB] text-white shadow-md' : 'text-[#1E3A8A]/60 hover:bg-[#EFF6FF]' }} rounded-xl text-sm font-bold transition-all">Creative</a>
                <a href="{{ route('admin.users', ['type' => 'umkm']) }}" class="px-5 py-2.5 {{ $type === 'umkm' ? 'bg-[#2563EB] text-white shadow-md' : 'text-[#1E3A8A]/60 hover:bg-[#EFF6FF]' }} rounded-xl text-sm font-bold transition-all">UMKM</a>
            </div>
        </div>

        @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center gap-3 animate-fade-in">
            <i class="fas fa-check-circle"></i>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
        @endif

        <!-- Users Table Card -->
        <div class="bg-white rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#F8FAFC] border-b border-[#2563EB]/5">
                            <th class="px-8 py-6 text-xs font-extrabold uppercase tracking-widest text-[#1E3A8A]/40">User</th>
                            <th class="px-8 py-6 text-xs font-extrabold uppercase tracking-widest text-[#1E3A8A]/40">Tipe</th>
                            <th class="px-8 py-6 text-xs font-extrabold uppercase tracking-widest text-[#1E3A8A]/40">Status</th>
                            <th class="px-8 py-6 text-xs font-extrabold uppercase tracking-widest text-[#1E3A8A]/40">Peringatan</th>
                            <th class="px-8 py-6 text-xs font-extrabold uppercase tracking-widest text-[#1E3A8A]/40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#2563EB]/5">
                        @foreach($users as $u)
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
                                    <button onclick="openWarnModal('{{ $u->id }}', '{{ $u->name }}')" class="p-2.5 bg-amber-50 text-amber-600 rounded-xl hover:bg-amber-600 hover:text-white transition-all shadow-sm" title="Beri Peringatan">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </button>
                                    
                                    <!-- Suspend/Activate Toggle -->
                                    @if($u->status === 'suspended')
                                        <form action="{{ route('admin.users.activate', $u->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-600 hover:text-white transition-all shadow-sm" title="Aktifkan Kembali">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.users.suspend', $u->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menangguhkan akun ini?')">
                                            @csrf
                                            <button type="submit" class="p-2.5 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm" title="Tangguhkan Akun">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
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
