<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Creative Worker - Konekin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Konekin - Creative Worker</h1>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-gray-600">Halo, {{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if (session('success'))
            <div class="mb-6 rounded-md bg-green-50 p-4">
                <div class="text-sm text-green-700">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Profile Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Profil Saya</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Nama</p>
                        <p class="font-medium text-gray-900">{{ auth()->user()->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-medium text-gray-900">{{ auth()->user()->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tipe Akun</p>
                        <p class="font-medium text-gray-900">Creative Worker</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nomor Telepon</p>
                        <p class="font-medium text-gray-900">{{ auth()->user()->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Kota</p>
                        <p class="font-medium text-gray-900">{{ auth()->user()->city ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="md:col-span-2 space-y-6">
                <!-- Statistics -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white rounded-lg shadow p-6">
                        <p class="text-sm text-gray-600 mb-2">Total Proyek</p>
                        <p class="text-3xl font-bold text-gray-900">0</p>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <p class="text-sm text-gray-600 mb-2">Earn</p>
                        <p class="text-3xl font-bold text-gray-900">Rp 0</p>
                    </div>
                </div>

                <!-- Edit Profile -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Edit Profil</h2>
                    <form action="{{ route('dashboard.update-profile') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                            <input type="text" id="name" name="name" value="{{ auth()->user()->name }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                            <input type="tel" id="phone" name="phone" value="{{ auth()->user()->phone }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                            <input type="text" id="address" name="address" value="{{ auth()->user()->address }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Kota</label>
                            <input type="text" id="city" name="city" value="{{ auth()->user()->city }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                            <textarea id="bio" name="bio" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" rows="4">{{ auth()->user()->bio }}</textarea>
                        </div>

                        <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>

                <!-- Upcoming Features -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Fitur Lainnya (Coming Soon)</h2>
                    <ul class="space-y-3">
                        <li class="flex items-center text-gray-700">
                            <span class="mr-3">📁</span> Kelola Portfolio
                        </li>
                        <li class="flex items-center text-gray-700">
                            <span class="mr-3">💰</span> Kelola Penghasilan
                        </li>
                        <li class="flex items-center text-gray-700">
                            <span class="mr-3">⭐</span> Rating & Review
                        </li>
                        <li class="flex items-center text-gray-700">
                            <span class="mr-3">🤝</span> Koneksi UMKM
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
