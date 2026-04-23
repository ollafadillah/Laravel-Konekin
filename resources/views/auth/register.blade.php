<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Konekin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-screen">
    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-2xl">
            <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl overflow-hidden">
                
                <!-- Header dengan gradient -->
                <div class="bg-gradient-to-r {{ ($type ?? old('type')) === 'umkm' ? 'from-blue-600 to-blue-700' : 'from-purple-600 to-purple-700' }} px-8 pt-12 pb-8 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 {{ ($type ?? old('type')) === 'umkm' ? 'bg-blue-400' : 'bg-purple-400' }}/20 rounded-full -mr-20 -mt-20"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-center mb-4">
                            <div class="w-16 h-16 {{ ($type ?? old('type')) === 'umkm' ? 'bg-blue-500' : 'bg-purple-500' }} rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="fas {{ ($type ?? old('type')) === 'umkm' ? 'fa-briefcase' : 'fa-palette' }} text-2xl"></i>
                            </div>
                        </div>
                        <h2 class="text-center text-4xl font-bold mb-2">Bergabunglah Sekarang</h2>
                        <p class="text-center text-{{ ($type ?? old('type')) === 'umkm' ? 'blue' : 'purple' }}-100 text-lg">Sebagai {{ ($type ?? old('type')) === 'umkm' ? 'UMKM / Bisnis' : 'Creative Worker' }}</p>
                    </div>
                </div>

                <!-- Form Container -->
                <div class="px-8 py-12">
                    
                    <!-- Progress Indicator -->
                    <div class="mb-10">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm font-semibold {{ ($type ?? old('type')) === 'umkm' ? 'text-blue-600' : 'text-purple-600' }}">Langkah 2 dari 2</span>
                            <span class="text-sm text-gray-500">Data Diri</span>
                        </div>
                        <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full {{ ($type ?? old('type')) === 'umkm' ? 'bg-gradient-to-r from-blue-500 to-blue-600' : 'bg-gradient-to-r from-purple-500 to-purple-600' }}" style="width: 100%;"></div>
                        </div>
                    </div>

                    <form class="space-y-6" action="{{ route('register.post') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type ?? old('type') }}">

                        <!-- Tipe User - Status Display -->
                        <div class="mb-8 p-4 {{ ($type ?? old('type')) === 'umkm' ? 'bg-blue-50 border-2 border-blue-200' : 'bg-purple-50 border-2 border-purple-200' }} rounded-2xl">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 w-12 h-12 {{ ($type ?? old('type')) === 'umkm' ? 'bg-blue-600' : 'bg-purple-600' }} rounded-xl flex items-center justify-center">
                                    <i class="fas {{ ($type ?? old('type')) === 'umkm' ? 'fa-check' : 'fa-check' }} text-white text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-xs {{ ($type ?? old('type')) === 'umkm' ? 'text-blue-600' : 'text-purple-600' }} font-semibold uppercase tracking-wide">Tipe Akun Dipilih</p>
                                    <p class="text-lg font-bold {{ ($type ?? old('type')) === 'umkm' ? 'text-blue-900' : 'text-purple-900' }}">
                                        {{ ($type ?? old('type')) === 'umkm' ? 'UMKM / Bisnis' : 'Creative Worker' }}
                                    </p>
                                </div>
                                <div class="ml-auto">
                                    <a href="{{ route('register.role') }}" class="text-xs {{ ($type ?? old('type')) === 'umkm' ? 'text-blue-600 hover:text-blue-700' : 'text-purple-600 hover:text-purple-700' }} font-semibold underline">Ubah</a>
                                </div>
                            </div>
                        </div>

                        <!-- Grid Form -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Lengkap -->
                            <div class="md:col-span-2">
                                <label for="name" class="block text-sm font-semibold {{ ($type ?? old('type')) === 'umkm' ? 'text-blue-900' : 'text-purple-900' }} mb-3">
                                    <i class="fas fa-user mr-2 {{ ($type ?? old('type')) === 'umkm' ? 'text-blue-600' : 'text-purple-600' }}"></i>Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input id="name" name="name" type="text" class="w-full px-4 py-3 rounded-xl border-2 {{ ($type ?? old('type')) === 'umkm' ? 'border-blue-200 focus:border-blue-500' : 'border-purple-200 focus:border-purple-500' }} focus:outline-none focus:ring-2 {{ ($type ?? old('type')) === 'umkm' ? 'focus:ring-blue-200' : 'focus:ring-purple-200' }} transition-all @error('name') border-red-500 @enderror" placeholder="Masukkan nama lengkap Anda" value="{{ old('name') }}" required>
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600 flex items-center"><i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="md:col-span-2">
                                <label for="email" class="block text-sm font-semibold {{ ($type ?? old('type')) === 'umkm' ? 'text-blue-900' : 'text-purple-900' }} mb-3">
                                    <i class="fas fa-envelope mr-2 {{ ($type ?? old('type')) === 'umkm' ? 'text-blue-600' : 'text-purple-600' }}"></i>Email <span class="text-red-500">*</span>
                                </label>
                                <input id="email" name="email" type="email" class="w-full px-4 py-3 rounded-xl border-2 {{ ($type ?? old('type')) === 'umkm' ? 'border-blue-200 focus:border-blue-500' : 'border-purple-200 focus:border-purple-500' }} focus:outline-none focus:ring-2 {{ ($type ?? old('type')) === 'umkm' ? 'focus:ring-blue-200' : 'focus:ring-purple-200' }} transition-all @error('email') border-red-500 @enderror" placeholder="email@example.com" value="{{ old('email') }}" required>
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600 flex items-center"><i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nomor Telepon -->
                            <div>
                                <label for="phone" class="block text-sm font-semibold {{ ($type ?? old('type')) === 'umkm' ? 'text-blue-900' : 'text-purple-900' }} mb-3">
                                    <i class="fas fa-phone mr-2 {{ ($type ?? old('type')) === 'umkm' ? 'text-blue-600' : 'text-purple-600' }}"></i>Nomor Telepon
                                </label>
                                <input id="phone" name="phone" type="tel" class="w-full px-4 py-3 rounded-xl border-2 {{ ($type ?? old('type')) === 'umkm' ? 'border-blue-200 focus:border-blue-500' : 'border-purple-200 focus:border-purple-500' }} focus:outline-none focus:ring-2 {{ ($type ?? old('type')) === 'umkm' ? 'focus:ring-blue-200' : 'focus:ring-purple-200' }} transition-all" placeholder="08XX-XXXX-XXXX" value="{{ old('phone') }}">
                            </div>

                            <!-- Kota -->
                            <div>
                                <label for="city" class="block text-sm font-semibold {{ ($type ?? old('type')) === 'umkm' ? 'text-blue-900' : 'text-purple-900' }} mb-3">
                                    <i class="fas fa-map-marker-alt mr-2 {{ ($type ?? old('type')) === 'umkm' ? 'text-blue-600' : 'text-purple-600' }}"></i>Kota
                                </label>
                                <input id="city" name="city" type="text" class="w-full px-4 py-3 rounded-xl border-2 {{ ($type ?? old('type')) === 'umkm' ? 'border-blue-200 focus:border-blue-500' : 'border-purple-200 focus:border-purple-500' }} focus:outline-none focus:ring-2 {{ ($type ?? old('type')) === 'umkm' ? 'focus:ring-blue-200' : 'focus:ring-purple-200' }} transition-all" placeholder="Kota Anda" value="{{ old('city') }}">
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-semibold {{ ($type ?? old('type')) === 'umkm' ? 'text-blue-900' : 'text-purple-900' }} mb-3">
                                    <i class="fas fa-lock mr-2 {{ ($type ?? old('type')) === 'umkm' ? 'text-blue-600' : 'text-purple-600' }}"></i>Password <span class="text-red-500">*</span>
                                </label>
                                <input id="password" name="password" type="password" class="w-full px-4 py-3 rounded-xl border-2 {{ ($type ?? old('type')) === 'umkm' ? 'border-blue-200 focus:border-blue-500' : 'border-purple-200 focus:border-purple-500' }} focus:outline-none focus:ring-2 {{ ($type ?? old('type')) === 'umkm' ? 'focus:ring-blue-200' : 'focus:ring-purple-200' }} transition-all @error('password') border-red-500 @enderror" placeholder="Minimal 8 karakter" required>
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600 flex items-center"><i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Konfirmasi Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold {{ ($type ?? old('type')) === 'umkm' ? 'text-blue-900' : 'text-purple-900' }} mb-3">
                                    <i class="fas fa-lock mr-2 {{ ($type ?? old('type')) === 'umkm' ? 'text-blue-600' : 'text-purple-600' }}"></i>Konfirmasi Password <span class="text-red-500">*</span>
                                </label>
                                <input id="password_confirmation" name="password_confirmation" type="password" class="w-full px-4 py-3 rounded-xl border-2 {{ ($type ?? old('type')) === 'umkm' ? 'border-blue-200 focus:border-blue-500' : 'border-purple-200 focus:border-purple-500' }} focus:outline-none focus:ring-2 {{ ($type ?? old('type')) === 'umkm' ? 'focus:ring-blue-200' : 'focus:ring-purple-200' }} transition-all" placeholder="Ulangi password Anda" required>
                            </div>
                        </div>

                        <!-- Terms & Conditions -->
                        <div class="mt-8 p-4 {{ ($type ?? old('type')) === 'umkm' ? 'bg-blue-50 border border-blue-200' : 'bg-purple-50 border border-purple-200' }} rounded-xl">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" class="mt-1 w-5 h-5 {{ ($type ?? old('type')) === 'umkm' ? 'text-blue-600' : 'text-purple-600' }} rounded focus:ring-2" required>
                                <span class="text-sm text-gray-700">
                                    Saya menyetujui <a href="#" class="{{ ($type ?? old('type')) === 'umkm' ? 'text-blue-600 hover:text-blue-700' : 'text-purple-600 hover:text-purple-700' }} font-semibold">Syarat & Ketentuan</a> dan <a href="#" class="{{ ($type ?? old('type')) === 'umkm' ? 'text-blue-600 hover:text-blue-700' : 'text-purple-600 hover:text-purple-700' }} font-semibold">Kebijakan Privasi</a> Konekin
                                </span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full mt-8 py-4 px-6 font-bold text-lg text-white {{ ($type ?? old('type')) === 'umkm' ? 'bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800' : 'bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800' }} rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-rocket"></i>
                            Daftar Sekarang
                        </button>

                        <!-- Divider -->
                        <div class="relative my-8">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white text-gray-500">atau</span>
                            </div>
                        </div>
                    </form>

                    <!-- Login Link -->
                    <div class="text-center">
                        <p class="text-gray-600 mb-4">
                            Sudah punya akun?
                        </p>
                        <a href="{{ route('login') }}" class="{{ ($type ?? old('type')) === 'umkm' ? 'inline-block w-full py-3 px-6 font-semibold text-blue-600 border-2 border-blue-600 rounded-xl hover:bg-blue-50 transition-all' : 'inline-block w-full py-3 px-6 font-semibold text-purple-600 border-2 border-purple-600 rounded-xl hover:bg-purple-50 transition-all' }}">
                            <i class="fas fa-sign-in-alt mr-2"></i>Masuk ke Akun Saya
                        </a>
                    </div>

                    <!-- Back to Role Selection -->
                    <div class="text-center mt-4">
                        <a href="{{ route('register.role') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center justify-center gap-2">
                            <i class="fas fa-arrow-left"></i>Kembali ke Pemilihan Peran
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer Info -->
            <div class="mt-8 text-center">
                <p class="text-gray-400 text-sm">
                    <i class="fas fa-lock-alt mr-2"></i>Data Anda aman dan terlindungi dengan enkripsi SSL
                </p>
            </div>
        </div>
    </div>

    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
    </style>
</body>
</html>
