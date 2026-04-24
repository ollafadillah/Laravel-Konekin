<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pilih Peran - Konekin</title>
    
    <!-- Fonts --> 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
    
    <!-- Navbar (dashboard-style glassmorphism) -->
    <nav class="fixed w-full top-0 z-50 glass border-b border-[#2563EB]/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="relative w-11 h-11 flex items-center justify-center">
                        <div class="absolute inset-0 bg-[#2563EB] rounded-2xl rotate-3 group-hover:rotate-6 group-hover:scale-105 transition-all duration-300 shadow-lg shadow-[#2563EB]/30"></div>
                        <div class="absolute inset-0 bg-[#0A66C2] rounded-2xl -rotate-6 opacity-50 group-hover:-rotate-12 transition-all duration-300"></div>
                        <span class="relative text-white font-display font-bold text-xl">K</span>
                    </div>
                    <span class="font-display font-bold text-2xl text-[#1E3A8A] tracking-tight group-hover:text-[#2563EB] transition-colors">
                        Konekin<span class="text-[#2563EB]">.</span>
                    </span>
                </a>

                <!-- Right side: Register/Login -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-[#1E3A8A] font-bold text-sm hover:text-[#2563EB] transition-colors">Masuk</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        
        <!-- Hero Header (dashboard style) -->
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-2 bg-[#EFF6FF] text-[#2563EB] text-xs font-extrabold uppercase tracking-widest rounded-full mb-6">
                Mulai Perjalanan Kreatifmu
            </span>
            <h1 class="font-display text-4xl md:text-5xl font-bold text-[#1E3A8A] mb-6 leading-tight">
                Pilih Peran Anda
            </h1>
            <p class="text-[#1E3A8A]/60 font-medium text-xl max-w-2xl mx-auto">
                Bergabunglah sebagai Creative Worker atau UMKM untuk memulai kolaborasi kreatif di Konekin
            </p>
        </div>

        <!-- Role Selection Cards (dashboard stats style) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Creative Worker Card -->
            <a href="{{ route('register', ['type' => 'creative_worker']) }}" class="group">
                <div class="bg-white p-8 rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm hover:shadow-2xl hover:shadow-[#2563EB]/10 transition-all duration-300 hover:-translate-y-2 h-full">
                    <!-- Icon Header -->
                    <div class="flex items-center gap-4 mb-8">
                        <div class="p-4 bg-[#EFF6FF] group-hover:bg-[#2563EB] group-hover:text-white rounded-3xl transition-all duration-300">
                            <i class="fas fa-palette text-2xl text-[#2563EB] group-hover:text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-display font-bold text-[#1E3A8A] mb-1">Creative Worker</h3>
                            <span class="text-[#2563EB] text-sm font-bold uppercase tracking-wider bg-[#EFF6FF]/50 px-3 py-1 rounded-full">Akses Ribuan Proyek</span>
                        </div>
                    </div>
                    
                    <!-- Image -->
                    <div class="w-full h-64 rounded-[2rem] overflow-hidden mb-8 bg-gradient-to-br from-slate-50 to-slate-100 group-hover:from-[#2563EB]/5">
                        <img src="{{ asset('images/creativew.jpeg') }}" alt="Creative Worker" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    
                    <!-- Features -->
                    <div class="space-y-4 mb-8">
                        <div class="flex items-center gap-3 p-4 bg-[#F8FAFC] rounded-2xl group-hover:bg-[#2563EB]/5 transition-colors">
                            <div class="w-2 h-2 bg-[#2563EB] rounded-full"></div>
                            <span class="font-medium text-[#1E3A8A]/90">Portfolio profesional untuk showcase karya</span>
                        </div>
                        <div class="flex items-center gap-3 p-4 bg-[#F8FAFC] rounded-2xl group-hover:bg-[#2563EB]/5 transition-colors">
                            <div class="w-2 h-2 bg-[#2563EB] rounded-full"></div>
                            <span class="font-medium text-[#1E3A8A]/90">Pembayaran aman & terjamin</span>
                        </div>
                        <div class="flex items-center gap-3 p-4 bg-[#F8FAFC] rounded-2xl group-hover:bg-[#2563EB]/5 transition-colors">
                            <div class="w-2 h-2 bg-[#2563EB] rounded-full"></div>
                            <span class="font-medium text-[#1E3A8A]/90">Jangkau UMKM di seluruh Indonesia</span>
                        </div>
                    </div>
                    
                    <!-- CTA Badge -->
                    <div class="flex items-center justify-between pt-6 border-t border-[#2563EB]/10">
                        <span class="text-2xl font-display font-bold text-[#1E3A8A]">Mulai Berkarya</span>
                        <div class="px-6 py-3 bg-gradient-to-r from-[#2563EB] to-[#0A66C2] text-white font-bold rounded-2xl shadow-lg hover:shadow-xl transition-all group-hover:scale-105">
                            <i class="fas fa-arrow-right mr-2"></i>Pilih
                        </div>
                    </div>
                </div>
            </a>

            <!-- UMKM Card -->
            <a href="{{ route('register', ['type' => 'umkm']) }}" class="group">
                <div class="bg-white p-8 rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm hover:shadow-2xl hover:shadow-[#2563EB]/10 transition-all duration-300 hover:-translate-y-2 h-full">
                    <!-- Icon Header -->
                    <div class="flex items-center gap-4 mb-8">
                        <div class="p-4 bg-[#EFF6FF] group-hover:bg-[#2563EB] group-hover:text-white rounded-3xl transition-all duration-300">
                            <i class="fas fa-briefcase text-2xl text-[#2563EB] group-hover:text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-display font-bold text-[#1E3A8A] mb-1">UMKM / Bisnis</h3>
                            <span class="text-[#2563EB] text-sm font-bold uppercase tracking-wider bg-[#EFF6FF]/50 px-3 py-1 rounded-full">Temukan Talenta Terbaik</span>
                        </div>
                    </div>
                    
                    <!-- Image -->
                    <div class="w-full h-64 rounded-[2rem] overflow-hidden mb-8 bg-gradient-to-br from-slate-50 to-slate-100 group-hover:from-[#2563EB]/5">
                        <img src="{{ asset('images/umkm.jpg') }}" alt="UMKM" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    
                    <!-- Features -->
                    <div class="space-y-4 mb-8">
                        <div class="flex items-center gap-3 p-4 bg-[#F8FAFC] rounded-2xl group-hover:bg-[#2563EB]/5 transition-colors">
                            <div class="w-2 h-2 bg-[#2563EB] rounded-full"></div>
                            <span class="font-medium text-[#1E3A8A]/90">Kelola proyek dengan mudah</span>
                        </div>
                        <div class="flex items-center gap-3 p-4 bg-[#F8FAFC] rounded-2xl group-hover:bg-[#2563EB]/5 transition-colors">
                            <div class="w-2 h-2 bg-[#2563EB] rounded-full"></div>
                            <span class="font-medium text-[#1E3A8A]/90">Pilih creative worker terbaik</span>
                        </div>
                        <div class="flex items-center gap-3 p-4 bg-[#F8FAFC] rounded-2xl group-hover:bg-[#2563EB]/5 transition-colors">
                            <div class="w-2 h-2 bg-[#2563EB] rounded-full"></div>
                            <span class="font-medium text-[#1E3A8A]/90">Hasil berkualitas tinggi</span>
                        </div>
                    </div>
                    
                    <!-- CTA Badge -->
                    <div class="flex items-center justify-between pt-6 border-t border-[#2563EB]/10">
                        <span class="text-2xl font-display font-bold text-[#1E3A8A]">Mulai Kolaborasi</span>
                        <div class="px-6 py-3 bg-gradient-to-r from-[#2563EB] to-[#0A66C2] text-white font-bold rounded-2xl shadow-lg hover:shadow-xl transition-all group-hover:scale-105">
                            <i class="fas fa-arrow-right mr-2"></i>Pilih
                        </div>
                    </div>
                </div>
            </a>

        </div>

        <div class="mt-12 max-w-md mx-auto">
            <div class="flex items-center justify-center mb-6">
                <div class="h-px w-full bg-[#2563EB]/10"></div>
                <span class="px-4 text-xs text-[#1E3A8A]/40 font-bold bg-[#F8FAFC]">ATAU</span>
                <div class="h-px w-full bg-[#2563EB]/10"></div>
            </div>
            <a href="{{ route('auth.google') }}" class="w-full py-4 px-4 bg-white border-2 border-[#2563EB]/10 text-[#1E3A8A] font-bold rounded-2xl shadow-sm hover:bg-white hover:border-[#2563EB] transition-all duration-300 flex items-center justify-center gap-3 group">
                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Cepat dengan Google
            </a>
        </div>

        <!-- Login Footer Link (dashboard style) -->
        <div class="mt-20 text-center">
            <p class="text-[#1E3A8A]/40 text-lg font-bold mb-4">Sudah memiliki akun?</p>
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white border-2 border-[#2563EB] text-[#2563EB] font-bold rounded-3xl hover:bg-[#2563EB] hover:text-white shadow-lg hover:shadow-xl transition-all duration-300 text-lg">
                <i class="fas fa-sign-in-alt"></i>
                Masuk Sekarang
            </a>
        </div>
    </main>

</body>
</html>

