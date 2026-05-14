@extends('layouts.app')

@section('content')
    @php
        $platformFee = (int) ($payment->platform_fee ?? round($payment->amount * 0.15));
        $netAmount = (int) ($payment->net_amount ?? ($payment->amount - $platformFee));
    @endphp

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-5xl mx-auto">
            <a href="{{ route('payments.index') }}" class="text-blue-600 hover:underline mb-6 inline-block">Kembali ke
                Pembayaran</a>

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
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-4 rounded">{{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-4 rounded">{{ session('error') }}</div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-6">
                <div class="space-y-6">
                    <div class="bg-white rounded-lg shadow-md p-8">
                        <div
                            class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 border-b border-gray-200 pb-6 mb-6">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900 mb-2">Invoice Escrow</h1>
                                <p class="text-gray-600">Invoice: <strong>{{ $payment->payment_number }}</strong></p>
                                <p class="text-gray-500 text-sm">{{ optional($payment->created_at)->format('d M Y H:i') }}
                                </p>
                            </div>
                            <div>
                                @if ($payment->isPending())
                                    <span
                                        class="inline-block bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full font-semibold">Menunggu
                                        Transfer VA</span>
                                @elseif ($payment->isAwaitingVerification())
                                    <span
                                        class="inline-block bg-green-100 text-green-800 px-4 py-2 rounded-full font-semibold">Bukti
                                        Dikirim</span>
                                @elseif ($payment->isVerified())
                                    <span
                                        class="inline-block bg-blue-100 text-blue-800 px-4 py-2 rounded-full font-semibold">Dana
                                        Ditahan Escrow</span>
                                @elseif ($payment->isFailed())
                                    <span
                                        class="inline-block bg-red-100 text-red-800 px-4 py-2 rounded-full font-semibold">Ditolak</span>
                                @elseif ($payment->isCancelled())
                                    <span
                                        class="inline-block bg-gray-100 text-gray-800 px-4 py-2 rounded-full font-semibold">Dibatalkan</span>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Dari:</p>
                                <p class="font-semibold text-gray-900">PT. Konekin Indonesia</p>
                                <p class="text-sm text-gray-600">Escrow Platform</p>
                            </div>
                            <div class="md:text-right">
                                <p class="text-sm text-gray-600 mb-1">Untuk:</p>
                                <p class="font-semibold text-gray-900">{{ $payment->client_name }}</p>
                                <p class="text-sm text-gray-600">UMKM Pemilik Proyek</p>
                            </div>
                        </div>

                        @if ($project)
                            <div class="bg-gray-50 rounded p-4 mb-6">
                                <h3 class="font-semibold text-gray-900 mb-2">Informasi Proyek</h3>
                                <p class="text-sm text-gray-700"><strong>Judul:</strong> {{ $project->title }}</p>
                                <p class="text-sm text-gray-700"><strong>Kategori:</strong> {{ $project->category }}</p>
                                <p class="text-sm text-gray-700"><strong>Status:</strong>
                                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}</p>
                            </div>
                        @endif

                        <div class="overflow-x-auto mb-6">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b-2 border-gray-300">
                                        <th class="text-left py-3 text-gray-700 font-semibold">Deskripsi</th>
                                        <th class="text-right py-3 text-gray-700 font-semibold">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b border-gray-200">
                                        <td class="py-3 text-gray-900">{{ $payment->description }}</td>
                                        <td class="text-right py-3 font-semibold text-gray-900">Rp
                                            {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr class="border-b border-gray-200">
                                        <td class="py-3 text-gray-700">Platform fee 15%</td>
                                        <td class="text-right py-3 text-gray-700">Rp
                                            {{ number_format($platformFee, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr class="border-b border-gray-200">
                                        <td class="py-3 text-gray-700">Net untuk creative worker</td>
                                        <td class="text-right py-3 text-gray-700">Rp
                                            {{ number_format($netAmount, 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="flex justify-end">
                            <div class="w-72 flex justify-between items-center border-t-2 border-gray-300 pt-4">
                                <span class="text-gray-700">Total Transfer:</span>
                                <span class="font-bold text-2xl text-gray-900">Rp
                                    {{ number_format($payment->amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    @if ($payment->isPending())
                        <div class="bg-white rounded-lg shadow-md p-8">
                            <h2 class="text-xl font-bold text-gray-900 mb-2">Virtual Account Otomatis</h2>
                            <p class="text-sm text-gray-600 mb-6">Transfer sesuai total invoice, lalu upload bukti transfer.
                                Dana akan ditahan oleh platform sampai proyek disetujui selesai atau dispute diputuskan admin.
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                                <div class="rounded-lg bg-blue-50 border border-blue-200 p-4">
                                    <p class="text-xs font-bold text-blue-700 uppercase mb-1">Bank</p>
                                    <p class="font-bold text-blue-900">{{ $payment->virtual_account_bank ?? 'BCA' }}</p>
                                </div>
                                <div class="rounded-lg bg-blue-50 border border-blue-200 p-4">
                                    <p class="text-xs font-bold text-blue-700 uppercase mb-1">Nomor VA</p>
                                    <p class="font-mono font-bold text-lg text-blue-900">{{ $payment->virtual_account_number }}
                                    </p>
                                </div>
                                <div class="rounded-lg bg-blue-50 border border-blue-200 p-4">
                                    <p class="text-xs font-bold text-blue-700 uppercase mb-1">Batas Transfer</p>
                                    <p class="font-bold text-blue-900">
                                        {{ optional($payment->payment_due_at)->format('d M Y H:i') ?? '-' }}</p>
                                </div>
                            </div>

                            <form action="{{ route('payments.upload-proof', $payment->_id) }}" method="POST"
                                enctype="multipart/form-data" class="space-y-6">
                                @csrf
                                <div>
                                    <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal
                                        Pembayaran</label>
                                    <input type="date" name="payment_date" id="payment_date" required
                                        max="{{ now()->format('Y-m-d') }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>

                                <div>
                                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Metode
                                        Pembayaran</label>
                                    <select name="payment_method" id="payment_method" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="virtual_account" selected>Virtual Account</option>
                                        <option value="transfer">Transfer Bank</option>
                                        <option value="e-wallet">E-Wallet</option>
                                        <option value="other">Lainnya</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="proof_file" class="block text-sm font-medium text-gray-700 mb-2">File Bukti
                                        Pembayaran</label>
                                    <input type="file" name="proof_file" id="proof_file" accept=".pdf,.jpg,.jpeg,.png,.gif"
                                        required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                    <p class="text-sm text-gray-500 mt-2">PDF, JPG, PNG, atau GIF. Maksimal 10MB.</p>
                                </div>

                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan
                                        Tambahan</label>
                                    <textarea name="notes" id="notes" rows="4"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                                        placeholder="Opsional"></textarea>
                                </div>

                                <button type="submit"
                                    class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition">Upload
                                    Bukti Transfer</button>
                            </form>

                            <form action="{{ route('payments.cancel', $payment->_id) }}" method="POST" class="mt-4">
                                @csrf
                                <button type="submit"
                                    class="bg-gray-400 text-white px-6 py-2 rounded-lg font-medium hover:bg-gray-500 transition"
                                    onclick="return confirm('Apakah Anda yakin ingin membatalkan pembayaran ini?')">
                                    Batalkan Pembayaran
                                </button>
                            </form>
                        </div>
                    @elseif ($payment->isAwaitingVerification())
                        <div class="bg-green-50 border border-green-200 rounded-lg p-8">
                            <h3 class="font-bold text-green-800 mb-2">Bukti Pembayaran Telah Dikirim</h3>
                            <p class="text-green-700 text-sm mb-4">Admin akan memverifikasi pembayaran. Setelah valid, dana
                                masuk escrow platform dan belum dicairkan ke creative worker.</p>
                            @if ($payment->proof_file_url)
                                <a href="{{ $payment->proof_file_url }}" target="_blank"
                                    class="text-blue-600 hover:underline text-sm font-medium">Lihat bukti pembayaran</a>
                            @endif
                        </div>
                    @elseif ($payment->isVerified())
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-8">
                            <h3 class="font-bold text-blue-800 mb-2">Dana Sudah Ditahan Platform</h3>
                            <p class="text-blue-700 text-sm">Pembayaran sudah diverifikasi admin. Dana akan tetap tertahan
                                sampai UMKM menyetujui hasil akhir atau admin menyelesaikan dispute.</p>
                        </div>
                    @elseif ($payment->isFailed())
                        <div class="bg-red-50 border border-red-200 rounded-lg p-8">
                            <h3 class="font-bold text-red-800 mb-2">Pembayaran Ditolak</h3>
                            <p class="text-red-700 text-sm">
                                {{ $payment->rejection_reason ?: 'Silakan buat ulang pembayaran atau hubungi admin.' }}</p>
                        </div>
                    @endif
                </div>

                <aside class="bg-white rounded-lg shadow-md p-6 h-fit lg:sticky lg:top-6">
                    <h3 class="font-bold text-gray-900 mb-4">Ringkasan</h3>
                    <div class="space-y-4 text-sm">
                        <div>
                            <p class="text-xs text-gray-600 uppercase tracking-wide mb-1">No. Invoice</p>
                            <p class="font-mono font-semibold text-gray-900">{{ $payment->payment_number }}</p>
                        </div>
                        <div class="border-t border-gray-200 pt-4">
                            <p class="text-xs text-gray-600 uppercase tracking-wide mb-1">Total Transfer</p>
                            <p class="text-lg font-bold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="border-t border-gray-200 pt-4">
                            <p class="text-xs text-gray-600 uppercase tracking-wide mb-1">Platform Fee</p>
                            <p class="font-bold text-gray-900">Rp {{ number_format($platformFee, 0, ',', '.') }}</p>
                        </div>
                        <div class="border-t border-gray-200 pt-4">
                            <p class="text-xs text-gray-600 uppercase tracking-wide mb-1">Net Creative Worker</p>
                            <p class="font-bold text-gray-900">Rp {{ number_format($netAmount, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-amber-50 rounded border border-amber-200">
                        <p class="text-xs text-amber-700 font-semibold mb-2">Penting</p>
                        <p class="text-xs text-amber-700">Pembayaran ini bukan pencairan langsung. Dana ditahan di escrow
                            platform sampai hasil proyek disetujui atau dispute selesai.</p>
                    </div>
                </aside>
            </div>
        </div>
    </div>
@endsection