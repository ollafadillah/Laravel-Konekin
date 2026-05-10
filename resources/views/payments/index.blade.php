@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Manajemen Pembayaran</h1>
            <p class="text-gray-600">Kelola semua transaksi pembayaran proyek Anda di sini.</p>
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

        <!-- Filter Tabs -->
        <div class="mb-6 border-b border-gray-200">
            <div class="flex space-x-8">
                <a href="{{ route('payments.index') }}" class="py-2 px-1 border-b-2 border-blue-500 text-blue-600 font-medium">Semua</a>
                <a href="{{ route('payments.index') }}?status=pending" class="py-2 px-1 text-gray-600 hover:text-gray-900">Menunggu Pembayaran</a>
                <a href="{{ route('payments.index') }}?status=paid" class="py-2 px-1 text-gray-600 hover:text-gray-900">Terbayar</a>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">No. Invoice</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Proyek</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Jumlah</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tanggal Dibuat</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $payment)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $payment->payment_number }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @if ($payment->project)
                                    <a href="{{ route('projects.show', $payment->project_id) }}" class="text-blue-600 hover:underline">
                                        {{ Str::limit($payment->project->title, 40) }}
                                    </a>
                                @else
                                    <span class="text-gray-500">Proyek Tidak Ditemukan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $payment->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if ($payment->isPending())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        ⏳ Menunggu
                                    </span>
                                @elseif ($payment->isPaid())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        ✓ Terbayar
                                    </span>
                                @elseif ($payment->isFailed())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        ✗ Ditolak
                                    </span>
                                @elseif ($payment->isCancelled())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        — Dibatalkan
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('payments.show', $payment->_id) }}" class="text-blue-600 hover:underline font-medium">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p>Belum ada data pembayaran</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection
