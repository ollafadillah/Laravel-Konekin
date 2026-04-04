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
                <a href="{{ route('login') }}" class="text-[#1E3A8A] font-bold text-sm hover:text-[#2563EB] transition-colors">Masuk</a>
                <a href="{{ route('register') }}" class="bg-gradient-to-r from-[#2563EB] to-[#0A66C2] hover:from-[#1E3A8A] hover:to-[#2563EB] text-white px-6 py-3 rounded-2xl font-bold text-sm shadow-xl shadow-[#2563EB]/25 transition-all hover:-translate-y-1 active:translate-y-0 relative overflow-hidden group">
                    <span class="relative z-10">Daftar Sekarang</span>
                    <div class="absolute inset-0 h-full w-full bg-white/20 scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-300 ease-out"></div>
                </a>
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
