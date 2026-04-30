@extends('layouts.app')

@section('content')
<!-- Add padding-top to account for the fixed navbar from layout -->
<div class="bg-[#EEF4FF] min-h-screen">

    <!-- [SECTION 1] HERO -->
    <section class="bg-[#EEF4FF] pt-32 pb-20 md:pt-40 md:pb-24 px-6 md:px-20 max-w-7xl mx-auto">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Kiri (teks) -->
            <div class="space-y-8">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-[#DBEAFE] text-[#2563EB] font-bold text-xs tracking-[0.1em] uppercase">
                    <span class="w-2 h-2 rounded-full bg-[#2563EB]"></span>
                    TENTANG KONEKIN
                </div>
                
                <h1 class="font-display text-5xl md:text-[56px] font-bold text-[#1B3FA6] leading-[1.15]">
                    Kami Hadir untuk <br>
                    <span class="inline-block bg-[#2563EB] text-white px-3 py-1 mt-2 mb-2 rounded-xl">Menghubungkan</span> <br>
                    Kreativitas & Peluang
                </h1>
                
                <p class="text-lg text-[#475569] leading-[1.7] max-w-lg">
                    Konekin lahir dari satu keyakinan: setiap UMKM berhak mendapat kreator terbaik, dan setiap kreator berhak mendapat proyek yang bermakna.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 pt-2">
                    <a href="{{ route('kreator.index') }}" class="inline-flex justify-center items-center px-7 py-3.5 bg-[#1B3FA6] text-white rounded-2xl font-bold transition-transform hover:-translate-y-1">
                        Mulai Berkolaborasi &rarr;
                    </a>
                    <a href="#cara-kerja" class="inline-flex justify-center items-center px-7 py-3.5 bg-white text-[#1B3FA6] border-[1.5px] border-[#CBD5E1] rounded-2xl font-bold transition-all hover:border-[#1B3FA6] hover:bg-[#EEF4FF]">
                        Lihat Cara Kerja
                    </a>
                </div>
            </div>
            
            <!-- Kanan (visual) -->
            <div class="relative w-full h-[400px] md:h-[500px]">
                <!-- Decorative Elements -->
                <div class="absolute top-10 right-10 w-64 h-64 bg-[#DBEAFE] rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>
                <div class="absolute bottom-10 left-10 w-48 h-48 bg-[#4F8EF7]/20 rounded-full mix-blend-multiply filter blur-2xl opacity-70"></div>
                
                <!-- Main Illustration Card -->
                <div class="absolute inset-4 md:inset-8 bg-white rounded-3xl shadow-[0_4px_24px_rgba(37,99,235,0.08)] border border-[#DBEAFE]/50 flex items-center justify-center p-8 overflow-hidden relative">
                    <!-- Placeholder Illustration -->
                    <svg class="w-full h-full text-[#4F8EF7]" viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Creator -->
                        <path d="M120 180 C120 150, 150 150, 160 180 Z" fill="#2563EB"/>
                        <circle cx="140" cy="130" r="25" fill="#1B3FA6"/>
                        <!-- UMKM -->
                        <path d="M280 180 C280 150, 250 150, 240 180 Z" fill="#4F8EF7"/>
                        <circle cx="260" cy="130" r="25" fill="#1B3FA6"/>
                        <!-- Handshake connection line -->
                        <path d="M160 170 C200 190, 200 190, 240 170" stroke="#1B3FA6" stroke-width="8" stroke-linecap="round"/>
                        <!-- Stars -->
                        <path d="M200 80 L205 95 L220 100 L205 105 L200 120 L195 105 L180 100 L195 95 Z" fill="#2563EB"/>
                        <circle cx="220" cy="60" r="4" fill="#DBEAFE"/>
                    </svg>

                    <!-- Floating Card 1 -->
                    <div class="absolute top-8 left-8 bg-white px-4 py-3 rounded-2xl shadow-xl flex items-center gap-3 animate-bounce" style="animation-duration: 3s;">
                        <div class="w-8 h-8 rounded-full bg-[#DBEAFE] flex items-center justify-center text-[#2563EB]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-[#1B3FA6]">500+</p>
                            <p class="text-[10px] text-[#475569] uppercase font-bold tracking-wider">Kreator Aktif</p>
                        </div>
                    </div>

                    <!-- Floating Card 2 -->
                    <div class="absolute bottom-8 right-8 bg-white px-4 py-3 rounded-2xl shadow-xl flex items-center gap-3 animate-bounce" style="animation-duration: 4s; animation-delay: 1s;">
                        <div class="w-8 h-8 rounded-full bg-[#1B3FA6] flex items-center justify-center text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-[#1B3FA6]">200+</p>
                            <p class="text-[10px] text-[#475569] uppercase font-bold tracking-wider">UMKM Bergabung</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- [SECTION 2] KISAH KONEKIN -->
    <section class="bg-white py-16 md:py-24 px-6 md:px-20">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <span class="text-[#2563EB] text-xs uppercase font-bold tracking-[0.1em]">LATAR BELAKANG</span>
                <h2 class="font-display text-4xl md:text-5xl font-bold text-[#1B3FA6] mt-4 mb-12">Dari Masalah Nyata,<br>Lahir Solusi Nyata</h2>
            </div>
            
            <div class="grid md:grid-cols-2 gap-10 md:gap-16 mb-20 max-w-4xl mx-auto">
                <div class="bg-[#EEF4FF] p-8 rounded-3xl relative">
                    <div class="absolute -top-4 -left-4 text-6xl text-[#DBEAFE] font-serif">"</div>
                    <p class="text-[#475569] text-base leading-[1.7] relative z-10">
                        UMKM Indonesia punya potensi luar biasa, tapi sering kesulitan mengakses kreator berkualitas dengan biaya terjangkau. Mereka butuh partner untuk tumbuh.
                    </p>
                </div>
                <div class="bg-[#EEF4FF] p-8 rounded-3xl relative">
                    <div class="absolute -top-4 -left-4 text-6xl text-[#DBEAFE] font-serif">"</div>
                    <p class="text-[#475569] text-base leading-[1.7] relative z-10">
                        Di sisi lain, ribuan kreator berbakat Indonesia kesulitan menemukan klien yang tepat dan proyek yang berkelanjutan. Portofolio mereka butuh panggung.
                    </p>
                </div>
            </div>

            <!-- Timeline -->
            <div class="relative max-w-4xl mx-auto pt-8">
                <div class="absolute top-[42px] left-0 w-full h-[1px] bg-[#DBEAFE] z-0 hidden md:block"></div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative z-10">
                    <div class="text-center">
                        <div class="w-5 h-5 bg-[#2563EB] rounded-full mx-auto mb-4 border-4 border-white shadow-[0_0_0_2px_rgba(37,99,235,0.2)]"></div>
                        <h4 class="font-display font-bold text-xl text-[#1B3FA6] mb-2">2023</h4>
                        <p class="text-[#475569] font-medium text-sm">Ide Lahir</p>
                    </div>
                    <div class="text-center mt-8 md:mt-0">
                        <div class="w-5 h-5 bg-[#2563EB] rounded-full mx-auto mb-4 border-4 border-white shadow-[0_0_0_2px_rgba(37,99,235,0.2)]"></div>
                        <h4 class="font-display font-bold text-xl text-[#1B3FA6] mb-2">2024</h4>
                        <p class="text-[#475569] font-medium text-sm">Beta Launch</p>
                    </div>
                    <div class="text-center mt-8 md:mt-0">
                        <div class="w-5 h-5 bg-[#2563EB] rounded-full mx-auto mb-4 border-4 border-white shadow-[0_0_0_2px_rgba(37,99,235,0.2)]"></div>
                        <h4 class="font-display font-bold text-xl text-[#1B3FA6] mb-2">2025</h4>
                        <p class="text-[#475569] font-medium text-sm">Konekin Berkembang</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- [SECTION 3] VISI MISI & NILAI -->
    <section class="bg-[#EEF4FF] py-16 md:py-24 px-6 md:px-20">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <span class="text-[#2563EB] text-xs uppercase font-bold tracking-[0.1em]">TUJUAN KAMI</span>
                <h2 class="font-display text-4xl md:text-5xl font-bold text-[#1B3FA6] mt-4">Membangun Ekosistem Kreatif Indonesia</h2>
            </div>

            <!-- Visi Misi -->
            <div class="grid md:grid-cols-2 gap-8 mb-16">
                <div class="bg-white p-10 rounded-[24px] shadow-[0_4px_24px_rgba(37,99,235,0.08)] border border-[#DBEAFE]/30">
                    <div class="w-14 h-14 bg-[#EEF4FF] rounded-2xl flex items-center justify-center mb-6 text-[#2563EB]">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <h3 class="font-display text-2xl font-bold text-[#1B3FA6] mb-4">Visi</h3>
                    <p class="text-[#475569] leading-[1.7]">
                        Menjadi platform kolaborasi terdepan di Indonesia yang menjembatani talenta kreatif dengan peluang bisnis lokal, menciptakan pertumbuhan ekonomi yang inklusif dan berkelanjutan.
                    </p>
                </div>
                
                <div class="bg-white p-10 rounded-[24px] shadow-[0_4px_24px_rgba(37,99,235,0.08)] border border-[#DBEAFE]/30">
                    <div class="w-14 h-14 bg-[#EEF4FF] rounded-2xl flex items-center justify-center mb-6 text-[#2563EB]">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="font-display text-2xl font-bold text-[#1B3FA6] mb-4">Misi</h3>
                    <ul class="space-y-4 text-[#475569]">
                        <li class="flex items-start gap-3">
                            <span class="w-6 h-6 rounded-full bg-[#EEF4FF] text-[#2563EB] flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span class="leading-[1.7]">Menyediakan akses mudah bagi UMKM ke layanan kreatif berkualitas.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-6 h-6 rounded-full bg-[#EEF4FF] text-[#2563EB] flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span class="leading-[1.7]">Membuka peluang pendapatan nyata bagi pekerja kreatif lepas.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-6 h-6 rounded-full bg-[#EEF4FF] text-[#2563EB] flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span class="leading-[1.7]">Membangun komunitas profesional berbasis kolaborasi dan kepercayaan.</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- 4 Value Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <!-- Kolaboratif -->
                <div class="bg-white p-6 rounded-[24px] shadow-[0_4px_24px_rgba(37,99,235,0.05)] text-center transition-transform hover:-translate-y-1">
                    <div class="w-12 h-12 bg-[#EEF4FF] rounded-full flex items-center justify-center mx-auto mb-4 text-[#2563EB]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <h4 class="font-bold text-[#1B3FA6] mb-2">Kolaboratif</h4>
                    <p class="text-[#475569] text-sm">Bertumbuh bersama melalui kerja sama yang saling menguntungkan.</p>
                </div>
                <!-- Lokal & Bangga -->
                <div class="bg-white p-6 rounded-[24px] shadow-[0_4px_24px_rgba(37,99,235,0.05)] text-center transition-transform hover:-translate-y-1">
                    <div class="w-12 h-12 bg-[#EEF4FF] rounded-full flex items-center justify-center mx-auto mb-4 text-[#2563EB]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h4 class="font-bold text-[#1B3FA6] mb-2">Lokal & Bangga</h4>
                    <p class="text-[#475569] text-sm">Mengangkat potensi dan karya terbaik dari dalam negeri.</p>
                </div>
                <!-- Terpercaya -->
                <div class="bg-white p-6 rounded-[24px] shadow-[0_4px_24px_rgba(37,99,235,0.05)] text-center transition-transform hover:-translate-y-1">
                    <div class="w-12 h-12 bg-[#EEF4FF] rounded-full flex items-center justify-center mx-auto mb-4 text-[#2563EB]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h4 class="font-bold text-[#1B3FA6] mb-2">Terpercaya</h4>
                    <p class="text-[#475569] text-sm">Membangun ekosistem yang transparan, aman, dan dapat diandalkan.</p>
                </div>
                <!-- Berdampak -->
                <div class="bg-white p-6 rounded-[24px] shadow-[0_4px_24px_rgba(37,99,235,0.05)] text-center transition-transform hover:-translate-y-1">
                    <div class="w-12 h-12 bg-[#EEF4FF] rounded-full flex items-center justify-center mx-auto mb-4 text-[#2563EB]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                    <h4 class="font-bold text-[#1B3FA6] mb-2">Berdampak</h4>
                    <p class="text-[#475569] text-sm">Fokus pada hasil akhir yang memberikan pertumbuhan nyata.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- [SECTION 4] CARA KERJA -->
    <section id="cara-kerja" class="bg-white py-16 md:py-24 px-6 md:px-20">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <span class="text-[#2563EB] text-xs uppercase font-bold tracking-[0.1em]">BAGAIMANA KONEKIN BEKERJA</span>
                <h2 class="font-display text-4xl md:text-5xl font-bold text-[#1B3FA6] mt-4 mb-10">Simpel, Cepat, dan Tepat Sasaran</h2>
                
                <!-- Toggle Mode (JS Implementation) -->
                <div class="inline-flex bg-[#EEF4FF] rounded-full p-1 border border-[#DBEAFE]">
                    <button id="btn-umkm" onclick="toggleCaraKerja('umkm')" class="px-6 py-2.5 rounded-full text-sm font-bold transition-colors bg-[#1B3FA6] text-white">Untuk UMKM</button>
                    <button id="btn-kreator" onclick="toggleCaraKerja('kreator')" class="px-6 py-2.5 rounded-full text-sm font-bold transition-colors text-[#475569] hover:text-[#1B3FA6]">Untuk Kreator</button>
                </div>
            </div>

            <!-- Container Langkah -->
            <div class="relative">
                <!-- Garis horizontal background (desktop) -->
                <div class="hidden md:block absolute top-10 left-10 right-10 h-0.5 bg-[#DBEAFE] z-0"></div>

                <!-- Mode UMKM -->
                <div id="flow-umkm" class="grid grid-cols-1 md:grid-cols-4 gap-8 relative z-10 transition-opacity duration-300 opacity-100">
                    <!-- Step 1 -->
                    <div class="bg-white p-6 rounded-[20px] shadow-[0_4px_24px_rgba(37,99,235,0.06)] border border-[#DBEAFE]/50 relative mt-4 md:mt-0 group">
                        <div class="w-10 h-10 rounded-full bg-[#1B3FA6] text-white flex items-center justify-center font-bold absolute -top-5 left-6 shadow-md group-hover:scale-110 transition-transform">1</div>
                        <h4 class="font-bold text-[#1B3FA6] mt-6 mb-2">Posting Kebutuhan</h4>
                        <p class="text-[#475569] text-sm leading-[1.6]">Buat proyek dan jelaskan apa yang UMKM Anda butuhkan dengan detail dan budget.</p>
                    </div>
                    <!-- Step 2 -->
                    <div class="bg-white p-6 rounded-[20px] shadow-[0_4px_24px_rgba(37,99,235,0.06)] border border-[#DBEAFE]/50 relative mt-4 md:mt-0 group">
                        <div class="w-10 h-10 rounded-full bg-[#1B3FA6] text-white flex items-center justify-center font-bold absolute -top-5 left-6 shadow-md group-hover:scale-110 transition-transform">2</div>
                        <h4 class="font-bold text-[#1B3FA6] mt-6 mb-2">Browse Kreator</h4>
                        <p class="text-[#475569] text-sm leading-[1.6]">Lihat proposal yang masuk atau cari kreator berdasarkan portofolio yang relevan.</p>
                    </div>
                    <!-- Step 3 -->
                    <div class="bg-white p-6 rounded-[20px] shadow-[0_4px_24px_rgba(37,99,235,0.06)] border border-[#DBEAFE]/50 relative mt-4 md:mt-0 group">
                        <div class="w-10 h-10 rounded-full bg-[#1B3FA6] text-white flex items-center justify-center font-bold absolute -top-5 left-6 shadow-md group-hover:scale-110 transition-transform">3</div>
                        <h4 class="font-bold text-[#1B3FA6] mt-6 mb-2">Diskusi & Deal</h4>
                        <p class="text-[#475569] text-sm leading-[1.6]">Pilih kreator, sepakati ruang lingkup, dan amankan pembayaran via escrow.</p>
                    </div>
                    <!-- Step 4 -->
                    <div class="bg-white p-6 rounded-[20px] shadow-[0_4px_24px_rgba(37,99,235,0.06)] border border-[#DBEAFE]/50 relative mt-4 md:mt-0 group">
                        <div class="w-10 h-10 rounded-full bg-[#1B3FA6] text-white flex items-center justify-center font-bold absolute -top-5 left-6 shadow-md group-hover:scale-110 transition-transform">4</div>
                        <h4 class="font-bold text-[#1B3FA6] mt-6 mb-2">Proyek Selesai</h4>
                        <p class="text-[#475569] text-sm leading-[1.6]">Terima hasil kerja, berikan ulasan, dan nikmati perkembangan bisnis Anda.</p>
                    </div>
                </div>

                <!-- Mode Kreator (Hidden initially) -->
                <div id="flow-kreator" class="grid grid-cols-1 md:grid-cols-4 gap-8 relative z-10 transition-opacity duration-300 opacity-0 hidden absolute top-0 left-0 w-full">
                    <!-- Step 1 -->
                    <div class="bg-white p-6 rounded-[20px] shadow-[0_4px_24px_rgba(37,99,235,0.06)] border border-[#DBEAFE]/50 relative mt-4 md:mt-0 group">
                        <div class="w-10 h-10 rounded-full bg-[#1B3FA6] text-white flex items-center justify-center font-bold absolute -top-5 left-6 shadow-md group-hover:scale-110 transition-transform">1</div>
                        <h4 class="font-bold text-[#1B3FA6] mt-6 mb-2">Buat Portofolio</h4>
                        <p class="text-[#475569] text-sm leading-[1.6]">Lengkapi profil dan unggah karya terbaik Anda untuk menarik perhatian klien.</p>
                    </div>
                    <!-- Step 2 -->
                    <div class="bg-white p-6 rounded-[20px] shadow-[0_4px_24px_rgba(37,99,235,0.06)] border border-[#DBEAFE]/50 relative mt-4 md:mt-0 group">
                        <div class="w-10 h-10 rounded-full bg-[#1B3FA6] text-white flex items-center justify-center font-bold absolute -top-5 left-6 shadow-md group-hover:scale-110 transition-transform">2</div>
                        <h4 class="font-bold text-[#1B3FA6] mt-6 mb-2">Apply Proyek</h4>
                        <p class="text-[#475569] text-sm leading-[1.6]">Cari proyek UMKM yang cocok dan kirimkan proposal serta estimasi biaya.</p>
                    </div>
                    <!-- Step 3 -->
                    <div class="bg-white p-6 rounded-[20px] shadow-[0_4px_24px_rgba(37,99,235,0.06)] border border-[#DBEAFE]/50 relative mt-4 md:mt-0 group">
                        <div class="w-10 h-10 rounded-full bg-[#1B3FA6] text-white flex items-center justify-center font-bold absolute -top-5 left-6 shadow-md group-hover:scale-110 transition-transform">3</div>
                        <h4 class="font-bold text-[#1B3FA6] mt-6 mb-2">Presentasi Diri</h4>
                        <p class="text-[#475569] text-sm leading-[1.6]">Diskusikan ide Anda bersama klien dan capai kesepakatan yang menguntungkan.</p>
                    </div>
                    <!-- Step 4 -->
                    <div class="bg-white p-6 rounded-[20px] shadow-[0_4px_24px_rgba(37,99,235,0.06)] border border-[#DBEAFE]/50 relative mt-4 md:mt-0 group">
                        <div class="w-10 h-10 rounded-full bg-[#1B3FA6] text-white flex items-center justify-center font-bold absolute -top-5 left-6 shadow-md group-hover:scale-110 transition-transform">4</div>
                        <h4 class="font-bold text-[#1B3FA6] mt-6 mb-2">Mulai Berkarya</h4>
                        <p class="text-[#475569] text-sm leading-[1.6]">Kerjakan proyek, serahkan hasil, dan dapatkan pembayaran serta ulasan positif.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- [SECTION 5] DAMPAK / IMPACT -->
    <section class="bg-[#1B3FA6] py-16 md:py-24 px-6 md:px-20 relative overflow-hidden">
        <!-- Dekorasi -->
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-white opacity-5 rounded-full"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-white opacity-5 rounded-full"></div>
        
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="text-center mb-16">
                <span class="text-white/70 text-xs uppercase font-bold tracking-[0.1em] border border-white/20 px-4 py-2 rounded-full inline-block mb-6 backdrop-blur-sm">DAMPAK NYATA KONEKIN</span>
                <h2 class="font-display text-4xl md:text-5xl font-bold text-white">Angka yang Bicara Sendiri</h2>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="bg-white p-8 rounded-[20px] text-center shadow-2xl transform transition hover:-translate-y-2">
                    <h3 class="font-display text-4xl md:text-5xl font-bold text-[#1B3FA6] mb-2">500+</h3>
                    <p class="text-[#475569] text-sm font-medium uppercase tracking-wide">Creative Workers<br>Terdaftar</p>
                </div>
                <div class="bg-white p-8 rounded-[20px] text-center shadow-2xl transform transition hover:-translate-y-2">
                    <h3 class="font-display text-4xl md:text-5xl font-bold text-[#1B3FA6] mb-2">200+</h3>
                    <p class="text-[#475569] text-sm font-medium uppercase tracking-wide">UMKM<br>Terbantu</p>
                </div>
                <div class="bg-white p-8 rounded-[20px] text-center shadow-2xl transform transition hover:-translate-y-2">
                    <h3 class="font-display text-4xl md:text-5xl font-bold text-[#1B3FA6] mb-2">1.000+</h3>
                    <p class="text-[#475569] text-sm font-medium uppercase tracking-wide">Proyek<br>Diselesaikan</p>
                </div>
                <div class="bg-white p-8 rounded-[20px] text-center shadow-2xl transform transition hover:-translate-y-2">
                    <h3 class="font-display text-4xl md:text-5xl font-bold text-[#1B3FA6] mb-2">15+</h3>
                    <p class="text-[#475569] text-sm font-medium uppercase tracking-wide">Kota di<br>Indonesia</p>
                </div>
            </div>
        </div>
    </section>

    <!-- [SECTION 6] TIM KONEKIN -->
    <section class="bg-[#EEF4FF] py-16 md:py-24 px-6 md:px-20">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <span class="text-[#2563EB] text-xs uppercase font-bold tracking-[0.1em]">ORANG DI BALIK KONEKIN</span>
                <h2 class="font-display text-4xl md:text-5xl font-bold text-[#1B3FA6] mt-4">Tim yang Percaya pada Kolaborasi</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Member 1 -->
                <div class="bg-white p-8 rounded-2xl shadow-[0_4px_24px_rgba(37,99,235,0.06)] border border-[#DBEAFE]/40 text-center relative group">
                    <div class="absolute top-6 right-6 text-[#CBD5E1] group-hover:text-[#2563EB] transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                    </div>
                    <img src="https://ui-avatars.com/api/?name=Andi+Susanto&background=2563EB&color=fff&size=200" alt="Andi Susanto" class="w-24 h-24 rounded-full mx-auto mb-4 border-4 border-[#2563EB] shadow-lg object-cover">
                    <h3 class="text-lg font-bold text-[#1B3FA6] mb-1">Andi Susanto</h3>
                    <p class="text-sm text-[#64748B] mb-5">CEO & Founder</p>
                    <p class="text-sm text-[#2563EB] italic leading-[1.6]">"Misi kami sederhana: Tidak ada lagi talenta yang terbuang dan UMKM yang tertinggal."</p>
                </div>
                
                <!-- Member 2 -->
                <div class="bg-white p-8 rounded-2xl shadow-[0_4px_24px_rgba(37,99,235,0.06)] border border-[#DBEAFE]/40 text-center relative group">
                    <div class="absolute top-6 right-6 text-[#CBD5E1] group-hover:text-[#2563EB] transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                    </div>
                    <img src="https://ui-avatars.com/api/?name=Budi+Prakoso&background=2563EB&color=fff&size=200" alt="Budi Prakoso" class="w-24 h-24 rounded-full mx-auto mb-4 border-4 border-[#2563EB] shadow-lg object-cover">
                    <h3 class="text-lg font-bold text-[#1B3FA6] mb-1">Budi Prakoso</h3>
                    <p class="text-sm text-[#64748B] mb-5">CTO</p>
                    <p class="text-sm text-[#2563EB] italic leading-[1.6]">"Teknologi harus menjadi jembatan yang memudahkan, bukan tembok yang memisahkan."</p>
                </div>
                
                <!-- Member 3 -->
                <div class="bg-white p-8 rounded-2xl shadow-[0_4px_24px_rgba(37,99,235,0.06)] border border-[#DBEAFE]/40 text-center relative group">
                    <div class="absolute top-6 right-6 text-[#CBD5E1] group-hover:text-[#2563EB] transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                    </div>
                    <img src="https://ui-avatars.com/api/?name=Citra+Kirana&background=2563EB&color=fff&size=200" alt="Citra Kirana" class="w-24 h-24 rounded-full mx-auto mb-4 border-4 border-[#2563EB] shadow-lg object-cover">
                    <h3 class="text-lg font-bold text-[#1B3FA6] mb-1">Citra Kirana</h3>
                    <p class="text-sm text-[#64748B] mb-5">Head of Community</p>
                    <p class="text-sm text-[#2563EB] italic leading-[1.6]">"Kekuatan utama Konekin ada pada penggunanya. Kami membangun relasi, bukan sekadar transaksi."</p>
                </div>
            </div>
        </div>
    </section>

    <!-- [SECTION 7] CTA PENUTUP -->
    <section class="grid md:grid-cols-2 h-auto min-h-[400px]">
        <!-- Kiri -->
        <div class="bg-[#1B3FA6] py-16 px-10 md:p-20 flex flex-col justify-center relative overflow-hidden group">
            <div class="absolute inset-0 bg-[radial-gradient(#ffffff20_1px,transparent_1px)] bg-[size:1.5rem_1.5rem] opacity-30"></div>
            <div class="relative z-10 max-w-md ml-auto md:mr-10">
                <span class="inline-block text-xs uppercase font-bold tracking-[0.1em] text-white/80 border border-white/30 rounded-full px-4 py-2 mb-6">UNTUK UMKM</span>
                <h2 class="font-display text-4xl font-bold text-white mb-4">Temukan Kreator yang Tepat untuk Bisnis Kamu</h2>
                <p class="text-white/70 mb-10 leading-[1.7] text-lg">Ratusan kreator tersedia, siap bantu UMKM kamu tumbuh lewat visual dan karya terbaik.</p>
                <a href="{{ route('kreator.index') }}" class="inline-flex justify-center items-center gap-2 border-[1.5px] border-white text-white px-8 py-4 rounded-2xl font-bold hover:bg-white hover:text-[#1B3FA6] transition-all group-hover:shadow-[0_0_20px_rgba(255,255,255,0.3)]">
                    Cari Kreator Sekarang &rarr;
                </a>
            </div>
        </div>

        <!-- Kanan -->
        <div class="bg-[#2563EB] py-16 px-10 md:p-20 flex flex-col justify-center relative overflow-hidden group">
            <div class="absolute inset-0 bg-[radial-gradient(#ffffff20_1px,transparent_1px)] bg-[size:1.5rem_1.5rem] opacity-30"></div>
            <div class="relative z-10 max-w-md mr-auto md:ml-10">
                <span class="inline-block text-xs uppercase font-bold tracking-[0.1em] text-white/80 border border-white/30 rounded-full px-4 py-2 mb-6">UNTUK KREATOR</span>
                <h2 class="font-display text-4xl font-bold text-white mb-4">Dapatkan Proyek Nyata dari UMKM Lokal</h2>
                <p class="text-white/70 mb-10 leading-[1.7] text-lg">Bangun portofolio, dapatkan penghasilan, dan berikan dampak nyata untuk UMKM Indonesia.</p>
                <a href="{{ route('register') }}" class="inline-flex justify-center items-center gap-2 border-[1.5px] border-white text-white px-8 py-4 rounded-2xl font-bold hover:bg-white hover:text-[#2563EB] transition-all group-hover:shadow-[0_0_20px_rgba(255,255,255,0.3)]">
                    Daftar Jadi Kreator &rarr;
                </a>
            </div>
        </div>
    </section>

</div>

@push('scripts')
<script>
    function toggleCaraKerja(mode) {
        const btnUmkm = document.getElementById('btn-umkm');
        const btnKreator = document.getElementById('btn-kreator');
        const flowUmkm = document.getElementById('flow-umkm');
        const flowKreator = document.getElementById('flow-kreator');

        if (mode === 'umkm') {
            btnUmkm.className = 'px-6 py-2.5 rounded-full text-sm font-bold transition-colors bg-[#1B3FA6] text-white';
            btnKreator.className = 'px-6 py-2.5 rounded-full text-sm font-bold transition-colors text-[#475569] hover:text-[#1B3FA6]';
            
            flowKreator.classList.remove('opacity-100');
            flowKreator.classList.add('opacity-0');
            setTimeout(() => {
                flowKreator.classList.add('hidden', 'absolute');
                flowKreator.classList.remove('relative');
                
                flowUmkm.classList.remove('hidden', 'absolute');
                flowUmkm.classList.add('relative');
                // trigger reflow
                void flowUmkm.offsetWidth;
                flowUmkm.classList.remove('opacity-0');
                flowUmkm.classList.add('opacity-100');
            }, 300);

        } else {
            btnKreator.className = 'px-6 py-2.5 rounded-full text-sm font-bold transition-colors bg-[#1B3FA6] text-white';
            btnUmkm.className = 'px-6 py-2.5 rounded-full text-sm font-bold transition-colors text-[#475569] hover:text-[#1B3FA6]';
            
            flowUmkm.classList.remove('opacity-100');
            flowUmkm.classList.add('opacity-0');
            setTimeout(() => {
                flowUmkm.classList.add('hidden', 'absolute');
                flowUmkm.classList.remove('relative');
                
                flowKreator.classList.remove('hidden', 'absolute');
                flowKreator.classList.add('relative');
                // trigger reflow
                void flowKreator.offsetWidth;
                flowKreator.classList.remove('opacity-0');
                flowKreator.classList.add('opacity-100');
            }, 300);
        }
    }
</script>
@endpush
@endsection
