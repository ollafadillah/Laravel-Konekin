@if(auth()->check())
<nav class="fixed w-full top-0 z-50 glass border-b border-[#2563EB]/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group shrink-0">
                <div class="relative w-10 h-10 flex items-center justify-center">
                    <div class="absolute inset-0 bg-[#2563EB] rounded-xl rotate-3 group-hover:rotate-6 transition-all shadow-lg shadow-[#2563EB]/20"></div>
                    <span class="relative text-white font-display font-bold text-lg">K</span>
                </div>
                <span class="font-display font-bold text-xl tracking-tight text-[#1E3A8A]">Konekin<span class="text-[#2563EB]">.</span>Admin</span>
            </a>

            <!-- Navigation Links -->
            <div class="hidden md:flex items-center bg-white/50 px-6 py-2 rounded-full border border-white/80 shadow-sm gap-1">
                <a href="{{ route('dashboard.admin') }}" class="px-4 py-2 {{ request()->routeIs('dashboard.admin') ? 'bg-[#2563EB] text-white shadow-md shadow-[#2563EB]/20' : 'text-[#1E3A8A]/70 hover:text-[#2563EB]' }} rounded-full text-sm font-bold transition-all">Dashboard</a>
                <a href="{{ route('admin.users') }}" class="px-4 py-2 {{ request()->routeIs('admin.users') ? 'bg-[#2563EB] text-white shadow-md shadow-[#2563EB]/20' : 'text-[#1E3A8A]/70 hover:text-[#2563EB]' }} rounded-full text-sm font-bold transition-all">Users</a>
                <a href="{{ route('admin.projects') }}" class="px-4 py-2 {{ request()->routeIs('admin.projects') ? 'bg-[#2563EB] text-white shadow-md shadow-[#2563EB]/20' : 'text-[#1E3A8A]/70 hover:text-[#2563EB]' }} rounded-full text-sm font-bold transition-all">Projects</a>
                <a href="{{ route('admin.escrow.index') }}" class="px-4 py-2 {{ request()->routeIs('admin.escrow.index') ? 'bg-[#2563EB] text-white shadow-md shadow-[#2563EB]/20' : 'text-[#1E3A8A]/70 hover:text-[#2563EB]' }} rounded-full text-sm font-bold transition-all">Payments</a>
                <a href="{{ route('admin.jobs') }}" class="px-4 py-2 {{ request()->routeIs('admin.jobs') ? 'bg-[#2563EB] text-white shadow-md shadow-[#2563EB]/20' : 'text-[#1E3A8A]/70 hover:text-[#2563EB]' }} rounded-full text-sm font-bold transition-all">Activity</a>
            </div>

            <!-- Right Side: Profile & Logout -->
            <div class="flex items-center gap-4">
                <!-- Profile -->
                <div class="flex items-center gap-3 pl-4 border-l border-[#2563EB]/10 group/profile cursor-pointer hover:opacity-80 transition-all">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-[#1E3A8A] leading-none group-hover/profile:text-[#2563EB] transition-colors">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] font-bold text-[#2563EB] uppercase tracking-wider mt-1">Administrator</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#2563EB] to-[#0A66C2] p-0.5 shadow-lg shadow-[#2563EB]/20 group-hover/profile:scale-105 transition-transform">
                        <img src="{{ auth()->user()->profile_photo ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=2563EB&color=fff' }}" alt="Profile" class="w-full h-full object-cover rounded-[10px] border-2 border-white">
                    </div>
                </div>

                <!-- Logout Button -->
                <button onclick="confirmLogout()" class="p-2.5 bg-white border border-red-100 rounded-xl hover:bg-red-50 transition-all group ml-1" title="Logout">
                    <svg class="w-6 h-6 text-red-500 group-hover:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>

<!-- Logout Form & Logic -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
    @csrf
</form>

<!-- Logout Confirmation Modal -->
<div id="logout-modal" class="fixed inset-0 z-[100] hidden">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeLogoutModal()"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-[3rem] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-white/20">
                <div class="bg-white px-6 pt-10 pb-8 sm:px-10">
                    <div class="flex flex-col items-center">
                        <div class="relative mb-6">
                            <div class="w-24 h-24 rounded-3xl bg-gradient-to-br from-[#2563EB] to-[#0A66C2] p-1 shadow-2xl shadow-[#2563EB]/20 rotate-3">
                                <img src="{{ auth()->user()->profile_photo ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=2563EB&color=fff' }}" 
                                     class="w-full h-full object-cover rounded-[20px] border-4 border-white">
                            </div>
                        </div>
                        <div class="text-center">
                            <h3 class="text-2xl font-extrabold text-[#1E3A8A] tracking-tight mb-1">{{ auth()->user()->name }}</h3>
                            <p class="text-xs font-black text-[#2563EB] uppercase tracking-[0.2em] mb-6">Administrator</p>
                            <div class="inline-block px-4 py-2 bg-slate-50 rounded-2xl border border-slate-100 mb-2">
                                <p class="text-slate-500 font-medium">Apakah Anda yakin ingin logout dari panel admin?</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50/80 px-6 py-8 sm:px-10 flex flex-col gap-3">
                    <button type="button" onclick="document.getElementById('logout-form').submit()" 
                        class="w-full inline-flex justify-center rounded-2xl border border-transparent shadow-xl shadow-red-500/20 px-8 py-4 bg-red-600 text-base font-bold text-white hover:bg-red-700 transition-all">
                        Ya, Keluar
                    </button>
                    <button type="button" onclick="closeLogoutModal()" 
                        class="w-full inline-flex justify-center rounded-2xl border border-slate-200 shadow-sm px-8 py-4 bg-white text-base font-bold text-slate-600 hover:bg-slate-50 transition-all">
                        Batal
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
</script>
@endif
