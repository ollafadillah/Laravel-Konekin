@extends('layouts.app')

@section('content')
<div class="pt-32 pb-20 lg:pt-44 lg:pb-32 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto relative">
    
    <!-- Hero Section -->
    <div class="grid lg:grid-cols-2 gap-16 lg:gap-20 items-center">
        <div class="text-left flex flex-col items-start relative z-10">
            <!-- Creative Badge -->
            <div class="relative group cursor-pointer mb-8">
                <div class="absolute -inset-1 bg-gradient-to-r from-[#2563EB] to-[#0A66C2] rounded-full blur opacity-40 group-hover:opacity-75 transition duration-500"></div>
                <div class="relative inline-flex items-center gap-2.5 px-5 py-2.5 rounded-full bg-white border border-[#2563EB]/20 text-[#1E3A8A] font-bold text-xs tracking-widest uppercase shadow-sm">
                    <span class="flex h-2.5 w-2.5 rounded-full bg-[#2563EB] animate-ping absolute"></span>
                    <span class="flex h-2.5 w-2.5 rounded-full bg-[#2563EB]"></span>
                    Platform Kolaborasi Masa Depan
                </div>
            </div>
            
            <h1 class="font-display text-5xl md:text-6xl lg:text-[4rem] font-bold text-[#1E3A8A] mb-6 leading-[1.05]">
                Hubungkan <br>
                <div class="relative inline-block mt-2">
                    <span class="relative z-10 text-white px-2">Kreativitas</span>
                    <span class="absolute bottom-1 left-0 w-full h-2/3 bg-[#2563EB] -rotate-2 scale-110 rounded-xl z-0"></span>
                </div> <br>
                dengan <span class="text-[#0A66C2] underline decoration-4 decoration-[#2563EB]/30 underline-offset-8">Peluang UMKM</span>
            </h1>
            
            <p class="text-lg md:text-xl text-[#1E3A8A]/70 mb-10 leading-relaxed max-w-lg font-medium">
                Ekosistem kolaboratif di mana talenta <strong>Creative Worker</strong> bertemu dengan <strong>UMKM</strong> idaman. Bangun <em>brand</em>, ciptakan karya, dan melesat bersama.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-5 w-full sm:w-auto">
                <a href="{{ route('kreator.index') }}" class="group relative inline-flex justify-center items-center px-8 py-4 bg-[#1E3A8A] text-white rounded-2xl font-bold text-base shadow-xl shadow-[#1E3A8A]/20 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:shadow-[#2563EB]/30 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-[#2563EB] to-[#0A66C2] opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <span class="relative z-10 flex items-center gap-2">
                        Mulai Kolaborasi
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </span>
                </a>
                <a href="{{ route('about') }}" class="group inline-flex justify-center items-center px-8 py-4 bg-white hover:bg-[#EFF6FF] text-[#1E3A8A] border-2 border-[#2563EB]/20 hover:border-[#2563EB] rounded-2xl font-bold text-base shadow-sm transition-all duration-300 hover:-translate-y-1 gap-2">
                    <svg class="w-6 h-6 text-[#2563EB] group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Cara Kerja
                </a>
            </div>
        </div>

        <div class="relative w-full aspect-square lg:aspect-[4/4] mt-10 lg:mt-0 z-10 p-4">
            <!-- Creative overlapping background blocks -->
            <div class="absolute inset-0 bg-[#2563EB] rounded-[3rem] rotate-6 opacity-20 transform scale-95 transition-transform duration-700 hover:rotate-12"></div>
            <div class="absolute inset-0 bg-[#0A66C2] rounded-[3rem] -rotate-3 opacity-30 transform scale-100 transition-transform duration-700 hover:-rotate-6"></div>
            
            <div class="relative w-full h-full rounded-[2.5rem] overflow-hidden shadow-2xl bg-white border-4 border-white/50 z-20 group">
                <div class="absolute inset-0 bg-gradient-to-t from-[#1E3A8A] to-transparent opacity-0 group-hover:opacity-40 transition-opacity duration-500 z-10"></div>
                <img src="{{ asset('kreatif.jpg') }}" alt="Ilustrasi Konekin" class="absolute inset-0 w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700 ease-out">
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="mt-32 relative z-10">
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-[#2563EB]/5 p-10 lg:p-14 border border-[#2563EB]/10">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-10 text-center">
                <div class="relative">
                    <div class="font-display text-5xl font-bold text-[#2563EB] mb-2">500+</div>
                    <div class="text-[#1E3A8A] font-bold text-sm uppercase tracking-wider">Kreator Aktif</div>
                    <div class="hidden lg:block absolute right-0 top-1/2 -translate-y-1/2 w-px h-12 bg-[#2563EB]/20"></div>
                </div>
                <div class="relative">
                    <div class="font-display text-5xl font-bold text-[#0A66C2] mb-2">1K+</div>
                    <div class="text-[#1E3A8A] font-bold text-sm uppercase tracking-wider">UMKM Terdaftar</div>
                    <div class="hidden lg:block absolute right-0 top-1/2 -translate-y-1/2 w-px h-12 bg-[#2563EB]/20"></div>
                </div>
                <div class="relative">
                    <div class="font-display text-5xl font-bold text-[#1E3A8A] mb-2">800+</div>
                    <div class="text-[#1E3A8A] font-bold text-sm uppercase tracking-wider">Proyek Selesai</div>
                    <div class="hidden lg:block absolute right-0 top-1/2 -translate-y-1/2 w-px h-12 bg-[#2563EB]/20"></div>
                </div>
                <div>
                    <div class="font-display text-5xl font-bold text-[#2563EB] mb-2">99%</div>
                    <div class="text-[#1E3A8A] font-bold text-sm uppercase tracking-wider">Klien Puas</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Video Tayangan Section -->
    <div class="mt-40 max-w-5xl mx-auto relative z-10">
        <div class="text-center mb-16">
            <h2 class="font-display text-4xl lg:text-5xl font-bold text-[#1E3A8A] mb-4">Kenali Konekin Lebih Dekat</h2>
            <p class="text-[#0A66C2] text-xl font-medium max-w-2xl mx-auto">Kami merancang platform kolaborasi tanpa batas untuk mempertemukan ide-ide brilian.</p>
        </div>
        
        <div class="relative rounded-[2.5rem] overflow-hidden shadow-2xl shadow-[#1E3A8A]/20 bg-white border-8 border-white group aspect-video">
            <iframe 
                src="https://drive.google.com/file/d/1PRoT6M42Hu33drAq0EQwrDo-awZ3MsC2/preview" 
                class="absolute inset-0 w-full h-full border-0 transform group-hover:scale-[1.01] transition-transform duration-500 rounded-3xl"
                allow="autoplay"
                allowfullscreen>
            </iframe>
        </div>
        
        <!-- Decorative blobs behind video -->
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#2563EB]/20 rounded-full blur-2xl -z-10"></div>
        <div class="absolute -bottom-10 -left-10 w-60 h-60 bg-[#0A66C2]/20 rounded-full blur-3xl -z-10"></div>
    </div>

    <!-- CTA Section -->
    <div class="mt-40">
        <div class="relative rounded-[3rem] bg-[#1E3A8A] overflow-hidden p-14 lg:p-24 text-center shadow-2xl shadow-[#1E3A8A]/30 border border-white/10 group">
            
            <!-- Dynamic abstract art background -->
            <div class="absolute inset-0 bg-[#0A66C2] opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"></div>
            <div class="absolute top-[-50%] left-[-10%] w-[120%] h-[200%] bg-gradient-to-br from-[#2563EB]/40 to-transparent rotate-12 pointer-events-none"></div>
            <div class="absolute bottom-[-50%] right-[-10%] w-[80%] h-[150%] bg-gradient-to-tl from-[#0A66C2]/50 to-transparent -rotate-12 pointer-events-none"></div>
            
            <!-- Pattern -->
            <div class="absolute inset-0 bg-[radial-gradient(#ffffff20_1px,transparent_1px)] bg-[size:1.5rem_1.5rem] opacity-30 pointer-events-none"></div>
            
            <div class="relative z-20 max-w-3xl mx-auto">
                <h2 class="font-display text-4xl md:text-6xl font-bold text-white mb-8 leading-tight">Berani Wujudkan <br> Ide Terbaikmu?</h2>
                <p class="text-xl text-[#EFF6FF]/80 mb-12 font-medium">Buat akun hari ini, gratis! Bergabunglah dengan ribuan pekerja kreatif dan UMKM di Konekin.</p>
                <a href="{{ route('register') }}" class="inline-flex justify-center items-center px-10 py-5 bg-white text-[#1E3A8A] hover:text-[#2563EB] rounded-2xl font-bold text-lg shadow-xl hover:-translate-y-2 hover:shadow-2xl hover:shadow-white/20 transition-all duration-300">
                    Daftar Sekarang Secara Gratis
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
