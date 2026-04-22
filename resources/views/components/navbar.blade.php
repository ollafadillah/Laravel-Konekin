<header class="fixed w-full top-0 z-50 transition-all duration-300 backdrop-blur-xl bg-[#EFF6FF]/70 border-b border-[#2563EB]/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20 md:h-24 transition-all">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center gap-3 cursor-pointer group">
                <div class="relative w-11 h-11 flex items-center justify-center">
                    <div class="absolute inset-0 bg-[#2563EB] rounded-2xl rotate-3 group-hover:rotate-6 group-hover:scale-105 transition-all duration-300 shadow-lg shadow-[#2563EB]/30"></div>
                    <div class="absolute inset-0 bg-[#0A66C2] rounded-2xl -rotate-6 opacity-50 group-hover:-rotate-12 transition-all duration-300"></div>
                    <span class="relative text-white font-display font-bold text-xl">K</span>
                </div>
                <span class="font-display font-bold text-2xl text-[#1E3A8A] tracking-tight group-hover:text-[#2563EB] transition-colors">
                    Konekin<span class="text-[#2563EB]">.</span>
                </span>
            </div>

            <!-- Desktop Menu -->
            <nav class="hidden md:flex space-x-8 items-center bg-white/40 px-8 py-3 rounded-full border border-white/50 backdrop-blur-sm shadow-sm">
                <a href="{{ route('home') }}" class="text-[#1E3A8A] hover:text-[#2563EB] font-bold text-sm transition-all hover:-translate-y-0.5">Beranda</a>
                <a href="{{ route('kreator.index') }}" class="text-[#1E3A8A]/70 hover:text-[#2563EB] font-semibold text-sm transition-all hover:-translate-y-0.5">Cari Kreator</a>
                <a href="{{ route('umkm.index') }}" class="text-[#1E3A8A]/70 hover:text-[#2563EB] font-semibold text-sm transition-all hover:-translate-y-0.5">Proyek UMKM</a>
                <a href="{{ route('about') }}" class="text-[#1E3A8A]/70 hover:text-[#2563EB] font-semibold text-sm transition-all hover:-translate-y-0.5">Tentang Kami</a>
            </nav>

            <!-- Call to Action -->
            <div class="hidden md:flex items-center space-x-6">
                @if (auth()->check())
                    <span class="text-[#1E3A8A] font-bold text-sm">Halo, {{ auth()->user()->name }}</span>
                    <a href="{{ auth()->user()->isCreativeWorker() ? route('dashboard.creative') : route('dashboard.umkm') }}" class="text-[#1E3A8A] font-bold text-sm hover:text-[#2563EB] transition-colors">Dashboard</a>
                    <button onclick="confirmLogout()" class="p-2 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 transition-all group" title="Logout">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                @else
                    <a href="{{ route('login') }}" class="text-[#1E3A8A] font-bold text-sm hover:text-[#2563EB] transition-colors">Masuk</a>
                    <a href="{{ route('register') }}" class="bg-gradient-to-r from-[#2563EB] to-[#0A66C2] hover:from-[#1E3A8A] hover:to-[#2563EB] text-white px-6 py-3 rounded-2xl font-bold text-sm shadow-xl shadow-[#2563EB]/25 transition-all hover:-translate-y-1 active:translate-y-0 relative overflow-hidden group">
                        <span class="relative z-10">Daftar Sekarang</span>
                        <div class="absolute inset-0 h-full w-full bg-white/20 scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-300 ease-out"></div>
                    </a>
                @endif
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button class="text-[#1E3A8A] hover:text-[#2563EB] focus:outline-none p-2 bg-white/50 rounded-xl">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</header>

@if (auth()->check())
<!-- Logout Form & Logic -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
    @csrf
</form>

<!-- Logout Confirmation Modal -->
<div id="logout-modal" class="fixed inset-0 z-[100] hidden">
    <!-- Backdrop with blur effect -->
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeLogoutModal()"></div>

    <!-- Modal content container -->
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <!-- Modal panel -->
            <div class="relative transform overflow-hidden rounded-[3rem] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-white/20">
                <div class="bg-white px-6 pt-10 pb-8 sm:px-10">
                    <div class="flex flex-col items-center">
                        <!-- User Profile Info -->
                        <div class="relative mb-6">
                            <div class="w-24 h-24 rounded-3xl bg-gradient-to-br from-[#2563EB] to-[#0A66C2] p-1 shadow-2xl shadow-[#2563EB]/20 rotate-3 transition-transform hover:rotate-0">
                                <img src="{{ auth()->user()->profile_photo ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=random' }}" 
                                     alt="Profile" 
                                     class="w-full h-full object-cover rounded-[20px] border-4 border-white">
                            </div>
                            <div class="absolute -bottom-2 -right-2 bg-red-500 text-white p-2 rounded-xl shadow-lg border-4 border-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </div>
                        </div>

                        <div class="text-center">
                            <h3 class="text-2xl font-extrabold text-[#1E3A8A] tracking-tight mb-1" id="modal-title">
                                {{ auth()->user()->name }}
                            </h3>
                            <p class="text-xs font-black text-[#2563EB] uppercase tracking-[0.2em] mb-6">
                                {{ auth()->user()->type === 'creative_worker' ? 'Creative Worker' : 'Mitra UMKM' }}
                            </p>
                            
                            <div class="inline-block px-4 py-2 bg-slate-50 rounded-2xl border border-slate-100 mb-2">
                                <p class="text-slate-500 font-medium">Apakah Anda yakin ingin logout?</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50/80 px-6 py-8 sm:px-10 flex flex-col gap-3">
                    <button type="button" onclick="document.getElementById('logout-form').submit()" 
                        class="w-full inline-flex justify-center rounded-2xl border border-transparent shadow-xl shadow-red-500/20 px-8 py-4 bg-red-600 text-base font-bold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all hover:scale-[1.02] active:scale-95">
                        Ya, Keluar Sekarang
                    </button>
                    <button type="button" onclick="closeLogoutModal()" 
                        class="w-full inline-flex justify-center rounded-2xl border border-slate-200 shadow-sm px-8 py-4 bg-white text-base font-bold text-slate-600 hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2563EB] transition-all hover:scale-[1.02] active:scale-95">
                        Tetap di Sini
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmLogout() {
        document.getElementById('logout-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeLogoutModal() {
        document.getElementById('logout-modal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    window.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeLogoutModal();
        }
    });
</script>
@endif
