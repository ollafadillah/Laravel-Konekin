<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Konekin</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
        }
        .font-display {
            font-family: 'Space Grotesk', sans-serif;
        }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-[#F8FAFC] min-h-screen text-gray-800 antialiased selection:bg-brand-500 selection:text-white flex selection:bg-blue-500">

    <div class="hidden lg:flex lg:w-[45%] bg-[#1E3A8A] flex-col justify-between p-12 relative overflow-hidden">
        <!-- Background Image & Overlay -->
        <img src="{{ asset('images/creativew.jpeg') }}" class="absolute inset-0 w-full h-full object-cover opacity-50 z-0">
        <div class="absolute inset-0 bg-gradient-to-br from-[#1E3A8A] via-[#2563EB]/90 to-[#0A66C2]/80 z-0"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full bg-[radial-gradient(#ffffff10_1px,transparent_1px)] bg-[size:2rem_2rem] z-0"></div>

        <div class="relative z-10">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="relative w-12 h-12 flex items-center justify-center">
                    <div class="absolute inset-0 bg-white rounded-2xl rotate-3 group-hover:rotate-6 transition-transform shadow-xl"></div>
                    <span class="relative text-[#1E3A8A] font-display font-bold text-2xl">K</span>
                </div>
                <span class="font-display font-bold text-3xl text-white tracking-tight">Konekin<span class="text-[#2563EB]">.</span></span>
            </a>
        </div>

        <div class="relative z-10 max-w-md">    
            <h1 class="font-display text-5xl lg:text-6xl font-bold text-white leading-[1.1] mb-8">
                Wujudkan <br> 
                <span class="relative inline-block mt-2">
                    <span class="relative z-10 text-[#EFF6FF]">Kreativitas</span>
                    <span class="absolute bottom-2 left-0 w-full h-4 bg-[#2563EB]/40 -rotate-1 rounded-lg z-0"></span>
                </span> <br>
                Tanpa Batas
            </h1>
            <p class="text-[#EFF6FF]/80 text-xl leading-relaxed font-medium">
                Hubungkan ide brilian Anda dengan partner profesional untuk menciptakan dampak yang nyata.
            </p>
        </div>

        <div class="relative z-10 flex items-center gap-5 border-t border-white/10 pt-10">
            <div class="flex -space-x-4">
                @php
                    $fallbacks = [
                        'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?w=100&h=100&fit=crop',
                        'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&h=100&fit=crop',
                        'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop'
                    ];
                @endphp

                @if($previewUsers->count() > 0)
                    @foreach($previewUsers as $index => $user)
                        <img class="w-12 h-12 rounded-full border-4 border-[#1E3A8A] shadow-lg object-cover" 
                             src="{{ $user->profile_photo && str_starts_with($user->profile_photo, 'http') ? $user->profile_photo : ($user->profile_photo ? asset('storage/' . $user->profile_photo) : $fallbacks[$index % 3]) }}" 
                             alt="{{ $user->name }}">
                    @endforeach
                    @if($previewUsers->count() < 3)
                        @for($i = $previewUsers->count(); $i < 3; $i++)
                            <img class="w-12 h-12 rounded-full border-4 border-[#1E3A8A] shadow-lg object-cover" 
                                 src="{{ $fallbacks[$i] }}" 
                                 alt="User Preview">
                        @endfor
                    @endif
                @else
                    @foreach($fallbacks as $url)
                        <img class="w-12 h-12 rounded-full border-4 border-[#1E3A8A] shadow-lg object-cover" 
                             src="{{ $url }}" 
                             alt="User Preview">
                    @endforeach
                @endif
            </div>
            <p class="text-base text-[#EFF6FF]/70 font-medium tracking-wide">Join <strong class="text-white">1,000+</strong> active users</p>
        </div>
    </div>

    <div class="w-full lg:w-[55%] flex items-center justify-center p-6 sm:p-12 relative">
        <div class="w-full max-w-md bg-white rounded-[2.5rem] p-8 sm:p-12 shadow-2xl shadow-[#1E3A8A]/5 border border-[#EFF6FF]">
            
            <div class="text-center mb-10">
                <div class="lg:hidden flex justify-center mb-8">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <div class="relative w-10 h-10 flex items-center justify-center">
                            <div class="absolute inset-0 bg-[#2563EB] rounded-xl rotate-3 shadow-lg"></div>
                            <span class="relative text-white font-display font-bold text-xl">K</span>
                        </div>
                        <span class="font-display font-bold text-2xl text-[#1E3A8A]">Konekin<span class="text-[#2563EB]">.</span></span>
                    </a>
                </div>
                <h2 class="font-display text-3xl font-bold text-[#1E3A8A] mb-3">Selamat Datang 👋</h2>
                <p class="text-[#1E3A8A]/50 font-medium">Masuk untuk melanjutkan perjalanan kreatif Anda</p>
            </div>

            @if ($errors->has('email') || session('error'))
                <div class="mb-8 rounded-2xl bg-red-50 p-5 border border-red-100 flex items-start gap-4">
                    <div class="w-6 h-6 rounded-full bg-red-500 text-white flex items-center justify-center shrink-0 mt-0.5 shadow-sm">
                        <i class="fas fa-exclamation text-[10px]"></i>
                    </div>
                    <div class="text-sm font-semibold text-red-700 leading-tight">
                        {{ $errors->first('email') ?: session('error') }}
                    </div>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                @csrf

                <div class="space-y-2">
                    <label for="email" class="block text-sm font-bold text-[#1E3A8A] ml-1">Alamat Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-[#2563EB]">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required 
                            class="block w-full pl-11 pr-4 py-4 bg-gray-50/50 border-2 border-gray-100 rounded-[1.25rem] text-sm font-medium transition-all duration-300 outline-none focus:bg-white focus:border-[#2563EB] focus:ring-4 focus:ring-[#2563EB]/5 placeholder-gray-400" 
                            placeholder="nama@email.com">
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between ml-1">
                        <label for="password" class="block text-sm font-bold text-[#1E3A8A]">Password</label>
                        <a href="#" class="text-xs font-extrabold text-[#2563EB] hover:text-[#0A66C2] transition-colors uppercase tracking-wider">Lupa Sandi?</a>
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-[#2563EB]">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="password" name="password" type="password" required 
                            class="block w-full pl-11 pr-12 py-4 bg-gray-50/50 border-2 border-gray-100 rounded-[1.25rem] text-sm font-medium transition-all duration-300 outline-none focus:bg-white focus:border-[#2563EB] focus:ring-4 focus:ring-[#2563EB]/5 placeholder-gray-400" 
                            placeholder="••••••••">
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#2563EB] transition-colors focus:outline-none">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full py-4 px-4 bg-[#1E3A8A] hover:bg-[#2563EB] text-white font-bold rounded-[1.25rem] shadow-xl shadow-[#1E3A8A]/20 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:shadow-[#2563EB]/30 flex items-center justify-center gap-3">
                    Masuk Sekarang
                    <i class="fas fa-arrow-right text-xs"></i>
                </button>
            </form>

            <div class="mt-10 flex items-center justify-center">
                <div class="h-px w-full bg-[#EFF6FF]"></div>
                <span class="px-4 text-[10px] text-[#1E3A8A]/30 font-black tracking-[0.2em] bg-white">ATAU</span>
                <div class="h-px w-full bg-[#EFF6FF]"></div>
            </div>

            <div class="mt-10">
                <a href="{{ route('auth.google') }}" class="w-full py-4 px-4 bg-white border-2 border-[#EFF6FF] text-[#1E3A8A] font-bold rounded-[1.25rem] shadow-sm hover:bg-[#EFF6FF]/30 hover:border-[#2563EB]/20 transition-all duration-300 flex items-center justify-center gap-3 group">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    Masuk dengan Google
                </a>
            </div>

            <div class="mt-10 text-center space-y-6">
                <p class="text-sm text-[#1E3A8A]/60 font-medium">
                    Belum punya akun? 
                    <a href="{{ route('register.role') }}" class="font-bold text-[#2563EB] hover:text-[#1E3A8A] transition-all decoration-2 underline-offset-4 hover:underline">
                        Daftar Gratis
                    </a>
                </p>
                
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-2 text-xs font-bold text-[#1E3A8A]/30 hover:text-[#2563EB] transition-colors uppercase tracking-widest">
                    <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>