@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 pt-20 pb-12 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-extrabold text-[#1E3A8A]">Pilih peran Anda</h2>
            <p class="text-gray-500 mt-2">Sesuaikan pengalaman Anda di Konekin</p>
        </div>

        <form action="{{ route('register') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <label class="relative cursor-pointer group">
                    <input type="radio" name="type" value="creative_worker" class="peer hidden" required>
                    <div class="bg-white rounded-3xl p-6 shadow-md border-2 border-transparent transition-all duration-300 peer-checked:border-blue-600 peer-checked:ring-2 peer-checked:ring-blue-100 hover:shadow-xl">
                        <img src="{{ asset('images/creativew.jpeg') }}" alt="Creative Worker" class="w-full h-48 object-cover rounded-2xl mb-6">
                        <h3 class="text-xl font-bold text-[#1E3A8A] mb-4">Creative Worker</h3>
                        <ul class="space-y-3 text-sm text-gray-600">
                            <li class="flex items-center gap-2">
                                <span class="bg-blue-100 text-blue-600 rounded-full p-1"><i class="fas fa-check text-[10px]"></i></span>
                                Akses ribuan proyek UMKM
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="bg-blue-100 text-blue-600 rounded-full p-1"><i class="fas fa-check text-[10px]"></i></span>
                                Portfolio digital profesional
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="bg-blue-100 text-blue-600 rounded-full p-1"><i class="fas fa-check text-[10px]"></i></span>
                                Perlindungan pembayaran
                            </li>
                        </ul>
                    </div>
                    <div class="absolute top-4 right-4 hidden peer-checked:block bg-blue-600 text-white rounded-full p-1">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                </label>

                <label class="relative cursor-pointer group">
                    <input type="radio" name="type" value="umkm" class="peer hidden">
                    <div class="bg-white rounded-3xl p-6 shadow-md border-2 border-transparent transition-all duration-300 peer-checked:border-blue-600 peer-checked:ring-2 peer-checked:ring-blue-100 hover:shadow-xl">
                        <img src="{{ asset('images/umkm.jpg') }}" alt="UMKM" class="w-full h-48 object-cover rounded-2xl mb-6">
                        <h3 class="text-xl font-bold text-[#1E3A8A] mb-4">UMKM / Bisnis</h3>
                        <ul class="space-y-3 text-sm text-gray-600">
                            <li class="flex items-center gap-2">
                                <span class="bg-blue-100 text-blue-600 rounded-full p-1"><i class="fas fa-check text-[10px]"></i></span>
                                Temukan talenta kreatif terbaik
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="bg-blue-100 text-blue-600 rounded-full p-1"><i class="fas fa-check text-[10px]"></i></span>
                                Kelola proyek lebih efisien
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="bg-blue-100 text-blue-600 rounded-full p-1"><i class="fas fa-check text-[10px]"></i></span>
                                Hasil desain berkualitas tinggi
                            </li>
                        </ul>
                    </div>
                    <div class="absolute top-4 right-4 hidden peer-checked:block bg-blue-600 text-white rounded-full p-1">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                </label>

            </div>

            <div class="mt-12 text-center">
                <button type="submit" class="px-12 py-4 bg-blue-600 text-white font-bold rounded-full shadow-lg hover:bg-blue-700 hover:-translate-y-1 transition-all duration-300 text-lg w-full md:w-auto">
                    Lanjutkan
                </button>
            </div>
        </form>

        <div class="mt-8 text-center">
            <p class="text-gray-500">
                Sudah memiliki akun? 
                <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Masuk sekarang</a>
            </p>
        </div>
    </div>
</div>
@endsection
