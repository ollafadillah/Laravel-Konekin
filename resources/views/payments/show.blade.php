@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <a href="{{ route('payments.index') }}" class="text-blue-600 hover:underline mb-6 inline-block">
            ← Kembali ke Pembayaran
        </a>

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Detail Pembayaran</h1>
            <p class="text-gray-600">Invoice: <strong>{{ $payment->payment_number }}</strong></p>
        </div>

        <!-- Alert Messages -->
        @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-4 rounded">
                <strong>Terjadi Kesalahan:</strong>
                <ul class="mt-2 ml-4 list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-4 rounded">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="col-span-2">
                <!-- Invoice Section -->
                <div class="bg-white rounded-lg shadow-md p-8 mb-6">
                    <div class="border-b border-gray-200 pb-6 mb-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">Invoice Pembayaran</h2>
                                <p class="text-gray-600 text-sm">{{ $payment->created_at->format('d M Y H:i') }}</p>
                            </div>
                            <div class="text-right">
                                @if ($payment->isPending())
                                    <span class="inline-block bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full font-semibold">
                                        ⏳ Menunggu Pembayaran
                                    </span>
                                @elseif ($payment->isPaid())
                                    <span class="inline-block bg-green-100 text-green-800 px-4 py-2 rounded-full font-semibold">
                                        ✓ Terbayar
                                    </span>
                                @elseif ($payment->isFailed())
                                    <span class="inline-block bg-red-100 text-red-800 px-4 py-2 rounded-full font-semibold">
                                        ✗ Ditolak
                                    </span>
                                @elseif ($payment->isCancelled())
                                    <span class="inline-block bg-gray-100 text-gray-800 px-4 py-2 rounded-full font-semibold">
                                        — Dibatalkan
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Details -->
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Dari:</p>
                            <p class="font-semibold text-gray-900">PT. Konekin Indonesia</p>
                            <p class="text-sm text-gray-600">Platform Freelance Indonesia</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600 mb-1">Untuk:</p>
                            <p class="font-semibold text-gray-900">{{ $payment->client_name }}</p>
                            <p class="text-sm text-gray-600">Klien Proyek</p>
                        </div>
                    </div>

                    <!-- Invoice Items -->
                    <table class="w-full mb-6">
                        <thead>
                            <tr class="border-b-2 border-gray-300">
                                <th class="text-left py-3 text-gray-700 font-semibold">Deskripsi</th>
                                <th class="text-right py-3 text-gray-700 font-semibold">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-200">
                                <td class="py-3 text-gray-900">{{ $payment->description }}</td>
                                <td class="text-right py-3 font-semibold text-gray-900">
                                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Total -->
                    <div class="border-t-2 border-gray-300 pt-4 mb-6">
                        <div class="flex justify-end">
                            <div class="w-64">
                                <div class="flex justify-between mb-4">
                                    <span class="text-gray-700">Total:</span>
                                    <span class="font-bold text-2xl text-gray-900">
                                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Project Info -->
                    @if ($project)
                        <div class="bg-gray-50 rounded p-4 mb-6">
                            <h3 class="font-semibold text-gray-900 mb-2">Informasi Proyek</h3>
                            <p class="text-sm text-gray-700"><strong>Judul:</strong> {{ $project->title }}</p>
                            <p class="text-sm text-gray-700"><strong>Kategori:</strong> {{ $project->category }}</p>
                            <p class="text-sm text-gray-700"><strong>Status:</strong> {{ ucfirst($project->status) }}</p>
                        </div>
                    @endif
                </div>

                <!-- Payment Proof Section -->
                @if ($payment->isPending())
                    <div class="bg-white rounded-lg shadow-md p-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Upload Bukti Pembayaran</h3>

                        <form action="{{ route('payments.upload-proof', $payment->_id) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Tanggal Pembayaran -->
                            <div class="mb-6">
                                <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="payment_date" id="payment_date" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    max="{{ now()->format('Y-m-d') }}">
                                @error('payment_date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Metode Pembayaran -->
                            <div class="mb-6">
                                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                                    Metode Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <select name="payment_method" id="payment_method" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">-- Pilih Metode --</option>
                                    <option value="transfer">Transfer Bank</option>
                                    <option value="card">Kartu Kredit</option>
                                    <option value="e-wallet">E-Wallet (GCash, Dana, OVO)</option>
                                    <option value="other">Lainnya</option>
                                </select>
                                @error('payment_method')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Bukti Pembayaran -->
                            <div class="mb-6">
                                <label for="proof_file" class="block text-sm font-medium text-gray-700 mb-2">
                                    File Bukti Pembayaran (PDF, JPG, PNG) <span class="text-red-500">*</span>
                                </label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-blue-500 transition">
                                    <input type="file" name="proof_file" id="proof_file" accept=".pdf,.jpg,.jpeg,.png,.gif" required
                                        class="hidden" onchange="updateFileName(this)">
                                    <label for="proof_file" class="cursor-pointer">
                                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        <p class="text-gray-600 font-medium">Klik atau tarik file di sini</p>
                                        <p class="text-gray-500 text-sm">PDF, JPG, PNG (Max 10MB)</p>
                                    </label>
                                </div>
                                <p id="fileName" class="text-sm text-gray-600 mt-2"></p>
                                @error('proof_file')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Catatan -->
                            <div class="mb-6">
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Catatan Tambahan (Opsional)
                                </label>
                                <textarea name="notes" id="notes" rows="4"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Tambahkan informasi tambahan jika diperlukan..."></textarea>
                                @error('notes')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="flex gap-4">
                                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-700 transition">
                                    Upload Bukti Pembayaran
                                </button>
                                <form action="{{ route('payments.cancel', $payment->_id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="bg-gray-400 text-white px-6 py-2 rounded-lg font-medium hover:bg-gray-500 transition"
                                        onclick="return confirm('Apakah Anda yakin ingin membatalkan pembayaran ini?')">
                                        Batalkan Pembayaran
                                    </button>
                                </form>
                            </div>
                        </form>
                    </div>
                @elseif ($payment->isPaid())
                    <div class="bg-green-50 border border-green-200 rounded-lg p-8">
                        <div class="flex items-start gap-4">
                            <svg class="w-6 h-6 text-green-600 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <h3 class="font-bold text-green-800 mb-2">Pembayaran Telah Dikirimkan</h3>
                                <p class="text-green-700 text-sm mb-4">
                                    Bukti pembayaran Anda telah kami terima pada {{ $payment->payment_date->format('d M Y H:i') }}.
                                    Admin akan memverifikasi dalam waktu maksimal 1x24 jam.
                                </p>

                                @if ($payment->proof_file_url)
                                    <div class="bg-white rounded p-4 mb-4">
                                        <p class="text-sm font-medium text-gray-700 mb-2">File Bukti:</p>
                                        <div class="flex items-center justify-between bg-gray-50 p-3 rounded">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M8 16.5a1 1 0 11-2 0 1 1 0 012 0zM15 7H4v5h11V7z" />
                                                </svg>
                                                <span class="text-sm text-gray-700">Bukti_Pembayaran.{{ $payment->proof_file_type }}</span>
                                            </div>
                                            <a href="{{ $payment->proof_file_url }}" target="_blank" class="text-blue-600 hover:underline text-sm font-medium">
                                                Lihat
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                @if ($payment->notes_from_umkm)
                                    <div class="bg-white rounded p-4">
                                        <p class="text-sm font-medium text-gray-700 mb-2">Catatan Anda:</p>
                                        <p class="text-gray-600 text-sm">{{ $payment->notes_from_umkm }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @elseif ($payment->isFailed())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-8">
                        <div class="flex items-start gap-4">
                            <svg class="w-6 h-6 text-red-600 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <h3 class="font-bold text-red-800 mb-2">Pembayaran Ditolak</h3>
                                <p class="text-red-700 text-sm mb-4">
                                    Pembayaran Anda telah ditolak oleh admin pada {{ $payment->rejected_at->format('d M Y H:i') }}.
                                </p>

                                @if ($payment->rejection_reason)
                                    <div class="bg-white rounded p-4">
                                        <p class="text-sm font-medium text-gray-700 mb-2">Alasan Penolakan:</p>
                                        <p class="text-gray-600 text-sm">{{ $payment->rejection_reason }}</p>
                                    </div>
                                @endif

                                <p class="text-red-700 text-sm mt-4">
                                    Silakan hubungi admin untuk informasi lebih lanjut atau buat pembayaran baru.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-span-1">
                <!-- Summary Card -->
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                    <h3 class="font-bold text-gray-900 mb-4">Ringkasan</h3>

                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-gray-600 uppercase tracking-wide mb-1">No. Invoice</p>
                            <p class="font-mono text-sm font-semibold text-gray-900">{{ $payment->payment_number }}</p>
                        </div>

                        <div class="border-t border-gray-200 pt-4">
                            <p class="text-xs text-gray-600 uppercase tracking-wide mb-1">Status</p>
                            @if ($payment->isPending())
                                <p class="text-sm font-semibold text-yellow-600">⏳ Menunggu Pembayaran</p>
                            @elseif ($payment->isPaid())
                                <p class="text-sm font-semibold text-green-600">✓ Terbayar</p>
                            @elseif ($payment->isFailed())
                                <p class="text-sm font-semibold text-red-600">✗ Ditolak</p>
                            @elseif ($payment->isCancelled())
                                <p class="text-sm font-semibold text-gray-600">— Dibatalkan</p>
                            @endif
                        </div>

                        @if ($payment->payment_method)
                            <div class="border-t border-gray-200 pt-4">
                                <p class="text-xs text-gray-600 uppercase tracking-wide mb-1">Metode Pembayaran</p>
                                <p class="text-sm font-semibold text-gray-900">{{ ucfirst(str_replace('-', ' ', $payment->payment_method)) }}</p>
                            </div>
                        @endif

                        @if ($payment->payment_date)
                            <div class="border-t border-gray-200 pt-4">
                                <p class="text-xs text-gray-600 uppercase tracking-wide mb-1">Tanggal Pembayaran</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $payment->payment_date->format('d M Y') }}</p>
                            </div>
                        @endif

                        <div class="border-t border-gray-200 pt-4">
                            <p class="text-xs text-gray-600 uppercase tracking-wide mb-1">Jumlah Pembayaran</p>
                            <p class="text-lg font-bold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <!-- Help Text -->
                    <div class="mt-6 p-4 bg-blue-50 rounded">
                        <p class="text-xs text-blue-700 font-semibold mb-2">💡 Tips</p>
                        <p class="text-xs text-blue-600">Pastikan bukti pembayaran jelas dan lengkap. Admin akan memverifikasi dalam 1x24 jam.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateFileName(input) {
        const fileName = input.files[0]?.name || '';
        document.getElementById('fileName').textContent = fileName ? `✓ File dipilih: ${fileName}` : '';
    }
</script>
@endsection
