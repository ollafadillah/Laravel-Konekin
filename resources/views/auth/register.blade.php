<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - Konekin</title>
    
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
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        .role-creative {
            --primary-color: #7C3AED;
            --secondary-color: #5B21B6;
            --bg-light: #F5F3FF;
            --accent-color: #DDD6FE;
            --shadow-color: rgba(124, 58, 237, 0.2);
        }

        .role-umkm {
            --primary-color: #2563EB;
            --secondary-color: #0A66C2;
            --bg-light: #EFF6FF;
            --accent-color: #DBEAFE;
            --shadow-color: rgba(37, 99, 235, 0.2);
        }
    </style>
</head>
<body class="antialiased text-[#1E3A8A] {{ ($type ?? old('type')) === 'umkm' ? 'role-umkm' : 'role-creative' }}">
    
    <!-- Navbar -->
    <header class="fixed w-full top-0 z-50 transition-all duration-300 backdrop-blur-xl bg-white/70 border-b border-[#2563EB]/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center gap-3 cursor-pointer group">
                    <div class="relative w-11 h-11 flex items-center justify-center">
                        <div class="absolute inset-0 bg-[var(--primary-color)] rounded-2xl rotate-3 group-hover:rotate-6 group-hover:scale-105 transition-all duration-300 shadow-lg shadow-[var(--shadow-color)]"></div>
                        <div class="absolute inset-0 bg-[var(--secondary-color)] rounded-2xl -rotate-6 opacity-50 group-hover:-rotate-12 transition-all duration-300"></div>
                        <span class="relative text-white font-display font-bold text-xl">K</span>
                    </div>
                    <span class="font-display font-bold text-2xl text-[#1E3A8A] tracking-tight group-hover:text-[var(--primary-color)] transition-colors">
                        Konekin<span class="text-[var(--primary-color)]">.</span>
                    </span>
                </a>

                <!-- Login Link -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-[#1E3A8A] font-bold text-sm hover:text-[var(--primary-color)] transition-colors">Masuk</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="pt-32 pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto">
            
            <!-- Header Section -->
            <div class="text-center mb-10">
                <span class="inline-block px-4 py-2 bg-[var(--bg-light)] text-[var(--primary-color)] text-xs font-extrabold uppercase tracking-widest rounded-full mb-4">
                    Langkah 2 dari 2
                </span>
                <h1 class="font-display text-4xl md:text-5xl font-bold text-[#1E3A8A] mb-4">
                    Lengkapi Data Diri
                </h1>
                <p class="text-[#1E3A8A]/60 font-medium text-lg">
                    Daftar sebagai {{ ($type ?? old('type')) === 'umkm' ? 'UMKM / Bisnis' : 'Creative Worker' }}
                </p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-[2.5rem] border border-[var(--primary-color)]/5 shadow-xl shadow-[var(--shadow-color)] overflow-hidden">
                
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-[var(--primary-color)] to-[var(--secondary-color)] px-8 py-10 text-white relative overflow-hidden">
                    <!-- Decorative Elements -->
                    <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/5 rounded-full -ml-16 -mb-16"></div>
                    
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas {{ ($type ?? old('type')) === 'umkm' ? 'fa-briefcase' : 'fa-palette' }} text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-display font-bold">Bergabung dengan Konekin</h3>
                            <p class="text-blue-50 mt-1">Lengkapi profil {{ ($type ?? old('type')) === 'umkm' ? 'bisnis' : 'kreatif' }} Anda</p>
                        </div>
                    </div>
                </div>

                <!-- Form Content -->
                <div class="px-8 py-10">
                    
                    <!-- Role Status -->
                    <div class="mb-8 p-5 bg-[var(--bg-light)] rounded-2xl border border-[var(--primary-color)]/10 flex items-center gap-4">
                        <div class="w-12 h-12 bg-[var(--primary-color)] rounded-xl flex items-center justify-center shrink-0 shadow-lg shadow-[var(--shadow-color)]">
                            <i class="fas fa-check text-white"></i>
                        </div>
                        <div class="flex-grow">
                            <p class="text-xs text-[var(--primary-color)] font-extrabold uppercase tracking-wider">Tipe Akun</p>
                            <p class="text-lg font-display font-bold text-[#1E3A8A]">
                                {{ ($type ?? old('type')) === 'umkm' ? 'UMKM / Bisnis' : 'Creative Worker' }}
                            </p>
                        </div>
                        <a href="{{ route('register.role') }}" class="text-[var(--primary-color)] text-sm font-bold hover:underline">Ubah</a>
                    </div>

                    @if (session('error'))
                        <div class="mb-6 rounded-2xl bg-red-50 p-4 border border-red-100 flex items-start gap-3">
                            <i class="fas fa-circle-exclamation text-red-500 mt-0.5"></i>
                            <div class="text-sm font-medium text-red-700">
                                {{ session('error') }}
                            </div>
                        </div>
                    @endif

                    <form class="space-y-6" action="{{ route('register.post') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type ?? old('type') }}">

                        <!-- Grid Form -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Lengkap -->
                            <div class="md:col-span-2">
                                <label for="name" class="block text-sm font-bold text-[#1E3A8A] mb-3">
                                    <i class="fas fa-user mr-2 text-[var(--primary-color)]"></i>{{ ($type ?? old('type')) === 'umkm' ? 'Nama Bisnis / Owner' : 'Nama Lengkap' }} <span class="text-red-500">*</span>
                                </label>
                                <input id="name" name="name" type="text" class="w-full px-5 py-4 rounded-2xl border-2 border-[var(--primary-color)]/10 focus:border-[var(--primary-color)] focus:ring-4 focus:ring-[var(--primary-color)]/10 transition-all @error('name') border-red-500 @enderror" placeholder="{{ ($type ?? old('type')) === 'umkm' ? 'Nama bisnis atau nama Anda' : 'Masukkan nama lengkap Anda' }}" value="{{ old('name') }}" required>
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600 flex items-center"><i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="md:col-span-2">
                                <label for="email" class="block text-sm font-bold text-[#1E3A8A] mb-3">
                                    <i class="fas fa-envelope mr-2 text-[var(--primary-color)]"></i>Email <span class="text-red-500">*</span>
                                </label>
                                <input id="email" name="email" type="email" class="w-full px-5 py-4 rounded-2xl border-2 border-[var(--primary-color)]/10 focus:border-[var(--primary-color)] focus:ring-4 focus:ring-[var(--primary-color)]/10 transition-all @error('email') border-red-500 @enderror" placeholder="email@example.com" value="{{ old('email') }}" required>
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600 flex items-center"><i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nomor Telepon -->
                            <div>
                                <label for="phone" class="block text-sm font-bold text-[#1E3A8A] mb-3">
                                    <i class="fas fa-phone mr-2 text-[var(--primary-color)]"></i>Nomor Telepon
                                </label>
                                <input id="phone" name="phone" type="tel" class="w-full px-5 py-4 rounded-2xl border-2 border-[var(--primary-color)]/10 focus:border-[var(--primary-color)] focus:ring-4 focus:ring-[var(--primary-color)]/10 transition-all" placeholder="08XX-XXXX-XXXX" value="{{ old('phone') }}">
                            </div>

                            <!-- Kota -->
                            <div>
                                <label for="city" class="block text-sm font-bold text-[#1E3A8A] mb-3">
                                    <i class="fas fa-map-marker-alt mr-2 text-[var(--primary-color)]"></i>Kota
                                </label>
                                <input id="city" name="city" type="text" class="w-full px-5 py-4 rounded-2xl border-2 border-[var(--primary-color)]/10 focus:border-[var(--primary-color)] focus:ring-4 focus:ring-[var(--primary-color)]/10 transition-all" placeholder="Kota domisili" value="{{ old('city') }}">
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-bold text-[#1E3A8A] mb-3">
                                    <i class="fas fa-lock mr-2 text-[var(--primary-color)]"></i>Password <span class="text-red-500">*</span>
                                </label>
                                <input id="password" name="password" type="password" class="w-full px-5 py-4 rounded-2xl border-2 border-[var(--primary-color)]/10 focus:border-[var(--primary-color)] focus:ring-4 focus:ring-[var(--primary-color)]/10 transition-all @error('password') border-red-500 @enderror" placeholder="Minimal 8 karakter" required>
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600 flex items-center"><i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Konfirmasi Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-bold text-[#1E3A8A] mb-3">
                                    <i class="fas fa-lock mr-2 text-[var(--primary-color)]"></i>Konfirmasi Password <span class="text-red-500">*</span>
                                </label>
                                <input id="password_confirmation" name="password_confirmation" type="password" class="w-full px-5 py-4 rounded-2xl border-2 border-[var(--primary-color)]/10 focus:border-[var(--primary-color)] focus:ring-4 focus:ring-[var(--primary-color)]/10 transition-all" placeholder="Ulangi password" required>
                            </div>
                        </div>

                        <!-- Terms & Submit -->
                        <div class="pt-4">
                            <div class="flex items-start gap-3 mb-6">
                                <input type="checkbox" id="terms" class="mt-1 w-5 h-5 rounded border-2 border-[var(--primary-color)]/20 text-[var(--primary-color)] focus:ring-[var(--primary-color)] cursor-pointer" required>
                                <label for="terms" class="text-sm text-[#1E3A8A]/70 cursor-pointer">
                                    Saya menyetujui <a href="#" class="text-[var(--primary-color)] font-bold hover:underline">Syarat & Ketentuan</a> dan <a href="#" class="text-[var(--primary-color)] font-bold hover:underline">Kebijakan Privasi</a> Konekin
                                </label>
                            </div>
                            
                            <button type="submit" class="w-full py-4 bg-gradient-to-r from-[var(--primary-color)] to-[var(--secondary-color)] hover:from-[#1E3A8A] hover:to-[var(--primary-color)] text-white font-bold rounded-2xl shadow-xl shadow-[var(--shadow-color)] transition-all hover:-translate-y-1 active:translate-y-0 flex items-center justify-center gap-3 group">
                                <span>Daftar Sekarang</span>
                                <i class="fas fa-rocket group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-8 flex items-center justify-center">
                        <div class="h-px w-full bg-gray-200"></div>
                        <span class="px-4 text-xs text-gray-400 font-bold bg-white">ATAU</span>
                        <div class="h-px w-full bg-gray-200"></div>
                    </div>

                    <div class="mt-8">
                        <a href="{{ route('auth.google', ['type' => $type ?? old('type')]) }}" class="w-full py-4 px-4 bg-white border-2 border-gray-100 text-[#1E3A8A] font-bold rounded-2xl shadow-sm hover:bg-gray-50 hover:border-[var(--primary-color)]/30 transition-all duration-300 flex items-center justify-center gap-3">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                            </svg>
                            Daftar dengan Google
                        </a>
                    </div>

                    <!-- Login Link -->
                    <div class="mt-8 text-center pt-6 border-t border-[var(--primary-color)]/10">
                        <p class="text-[#1E3A8A]/60">
                            Sudah memiliki akun? 
                            <a href="{{ route('login') }}" class="text-[var(--primary-color)] font-bold hover:underline">Masuk sekarang</a>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Footer Info -->
            <div class="mt-8 text-center">
                <p class="text-gray-400 text-sm">
                    <i class="fas fa-lock mr-2"></i>Data Anda aman dan terlindungi dengan enkripsi SSL
                </p>
            </div>
        </div>
    </main>

</body>
</html>
