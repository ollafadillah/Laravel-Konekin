<nav class="fixed w-full top-0 z-50 glass border-b border-[#2563EB]/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group shrink-0">
                <div class="relative w-10 h-10 flex items-center justify-center">
                    <div class="absolute inset-0 bg-[#2563EB] rounded-xl rotate-3 group-hover:rotate-6 transition-all shadow-lg shadow-[#2563EB]/20"></div>
                    <span class="relative text-white font-display font-bold text-lg">K</span>
                </div>
                <span class="font-display font-bold text-xl tracking-tight">Konekin<span class="text-[#2563EB]">.</span></span>
            </a>

            <!-- Navigation Links -->
            <div class="hidden md:flex items-center bg-white/50 px-6 py-2 rounded-full border border-white/80 shadow-sm gap-1">
                <a href="{{ route('dashboard.creative') }}" class="px-4 py-2 {{ request()->routeIs('dashboard.creative') ? 'bg-[#2563EB] text-white shadow-md shadow-[#2563EB]/20' : 'text-[#1E3A8A]/70 hover:text-[#2563EB]' }} rounded-full text-sm font-bold transition-all">Dashboard</a>
                <a href="{{ route('projects.index') }}" class="px-4 py-2 {{ request()->routeIs('projects.index') ? 'bg-[#2563EB] text-white shadow-md shadow-[#2563EB]/20' : 'text-[#1E3A8A]/70 hover:text-[#2563EB]' }} rounded-full text-sm font-bold transition-all">Cari Proyek</a>
                <a href="{{ route('portfolio.index') }}" class="px-4 py-2 {{ request()->routeIs('portfolio.index') ? 'bg-[#2563EB] text-white shadow-md shadow-[#2563EB]/20' : 'text-[#1E3A8A]/70 hover:text-[#2563EB]' }} rounded-full text-sm font-bold transition-all">Portfolio</a>
                <a href="#" class="px-4 py-2 text-[#1E3A8A]/70 hover:text-[#2563EB] rounded-full text-sm font-bold transition-all">Pesan</a>
            </div>

            <!-- Right Side: Notification & Profile -->
            <div class="flex items-center gap-4">
                <!-- Notification Bell -->
                <button class="relative p-2.5 bg-white border border-[#2563EB]/10 rounded-xl hover:bg-[#EFF6FF] transition-all group">
                    <svg class="w-6 h-6 text-[#1E3A8A]/70 group-hover:text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="absolute top-2.5 right-2.5 flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                    </span>
                </button>

                <!-- Profile -->
                <a href="{{ route('profile.index') }}" class="flex items-center gap-3 pl-4 border-l border-[#2563EB]/10 group/profile cursor-pointer hover:opacity-80 transition-all">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-[#1E3A8A] leading-none group-hover/profile:text-[#2563EB] transition-colors">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] font-bold text-[#2563EB] uppercase tracking-wider mt-1">Creative Worker</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#2563EB] to-[#0A66C2] p-0.5 shadow-lg shadow-[#2563EB]/20 group-hover/profile:scale-105 transition-transform">
                        <img src="{{ auth()->user()->profile_photo ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=random' }}" alt="Profile" class="w-full h-full object-cover rounded-[10px] border-2 border-white">
                    </div>
                </a>
            </div>
        </div>
    </div>
</nav>
