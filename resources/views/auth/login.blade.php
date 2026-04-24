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

    <div class="hidden lg:flex lg:w-[45%] bg-gradient-to-br from-blue-600 to-blue-700 flex-col justify-between p-12 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-blue-700 to-blue-800 z-0"></div>
        <div class="absolute top-0 -left-4 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob z-0"></div>
        <div class="absolute top-0 -right-4 w-72 h-72 bg-brand-500 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000 z-0"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-500 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-4000 z-0"></div>

        <div class="relative z-10">
            <a href="{{ route('home') }}" class="font-heading text-2xl font-bold tracking-tight inline-flex items-center gap-2 text-white transition-transform hover:scale-105 duration-300">
                <div class="w-10 h-10 bg-brand-600 rounded-lg flex items-center justify-center shadow-lg">
                    <span class="text-white font-bold text-lg">K</span>
                </div>
                Konekin<span class="text-brand-500">.</span>
            </a>
        </div>

        <div class="relative z-10 max-w-md">
            <h1 class="font-heading text-5xl font-extrabold text-white leading-[1.1] mb-6">
                Temukan Peluang <br> 
                <span class="text-blue-200">
                    Tanpa Batas
                </span>
            </h1>
            <p class="text-indigo-100/80 text-lg leading-relaxed font-light">
                Platform kolaborasi terbaik yang menghubungkan inovasi UMKM dengan talenta kreatif profesional di seluruh Indonesia.
            </p>
        </div>

        <div class="relative z-10 flex items-center gap-4 border-t border-white/10 pt-8 mt-12">
            <div class="flex -space-x-3">
                <img class="w-10 h-10 rounded-full border-2 border-indigo-900" src="https://ui-avatars.com/api/?name=U&background=2563eb&color=fff" alt="User">
                <img class="w-10 h-10 rounded-full border-2 border-indigo-900" src="https://ui-avatars.com/api/?name=M&background=8b5cf6&color=fff" alt="User">
                <img class="w-10 h-10 rounded-full border-2 border-indigo-900" src="https://ui-avatars.com/api/?name=K&background=ec4899&color=fff" alt="User">
            </div>
            <p class="text-sm text-indigo-200">Bergabung dengan <strong class="text-white">10.000+</strong> pengguna lainnya</p>
        </div>
    </div>

    <div class="w-full lg:w-[55%] flex items-center justify-center p-6 sm:p-12 relative">
        <div class="w-full max-w-md bg-white rounded-[2.5rem] p-8 sm:p-12 shadow-2xl shadow-brand-900/5 border border-gray-100">
            
            <div class="text-center mb-10">
                <h2 class="font-heading text-3xl font-bold text-gray-900 mb-2">Selamat Datang! 👋</h2>
                <p class="text-gray-500">Masuk untuk melanjutkan perjalanan Anda</p>
            </div>

            @if ($errors->has('email') || session('error'))
                <div class="mb-6 rounded-2xl bg-red-50 p-4 border border-red-100 flex items-start gap-3 animate-pulse">
                    <i class="fas fa-circle-exclamation text-red-500 mt-0.5"></i>
                    <div class="text-sm font-medium text-red-700">
                        {{ $errors->first('email') ?: session('error') }}
                    </div>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                @csrf

                <div class="space-y-2">
                    <label for="email" class="block text-sm font-semibold text-gray-700">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required 
                            class="block w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm transition-all duration-200 outline-none focus:bg-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 placeholder-gray-400" 
                            placeholder="nama@email.com">
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                        <a href="#" class="text-sm font-semibold text-brand-600 hover:text-brand-700 transition-colors">Lupa sandi?</a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="password" name="password" type="password" required 
                            class="block w-full pl-11 pr-12 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm transition-all duration-200 outline-none focus:bg-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 placeholder-gray-400" 
                            placeholder="••••••••">
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full py-4 px-4 bg-gray-900 hover:bg-black text-white font-semibold rounded-2xl shadow-xl shadow-gray-900/20 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:shadow-gray-900/30">
                    Masuk Sekarang
                </button>
            </form>

            <div class="mt-8 flex items-center justify-center">
                <div class="h-px w-full bg-gray-200"></div>
                <span class="px-4 text-sm text-gray-400 font-medium bg-white">ATAU</span>
                <div class="h-px w-full bg-gray-200"></div>
            </div>

            <div class="mt-8">
                <a href="{{ route('auth.google') }}" class="w-full py-3.5 px-4 bg-white border-2 border-gray-100 text-gray-700 font-semibold rounded-2xl shadow-sm hover:bg-gray-50 hover:border-gray-200 transition-all duration-300 flex items-center justify-center gap-3">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    Masuk dengan Google
                </a>
            </div>

            <div class="mt-8 text-center space-y-4">
                <p class="text-sm text-gray-600">
                    Belum punya akun? 
                    <a href="{{ route('register.role') }}" class="font-bold text-brand-600 hover:text-brand-700 hover:underline underline-offset-4 transition-all">
                        Daftar di sini
                    </a>
                </p>
                
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-2 text-sm font-medium text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-arrow-left text-xs"></i> Kembali ke Beranda
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