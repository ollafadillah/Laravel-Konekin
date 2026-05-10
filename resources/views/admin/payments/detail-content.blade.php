<div class="space-y-6">
    <!-- UMKM Info -->
    <div>
        <h4 class="text-sm font-bold text-[#1E3A8A] mb-3">Informasi UMKM</h4>
        <div class="flex items-center gap-4 p-4 bg-blue-50 rounded-2xl">
            @if($payment->client_avatar)
                <img src="{{ $payment->client_avatar }}" alt="{{ $payment->client_name }}" class="w-12 h-12 rounded-lg object-cover">
            @else
                <div class="w-12 h-12 rounded-lg bg-[#2563EB]/10 flex items-center justify-center">
                    <i class="fas fa-building text-[#2563EB]/40 text-lg"></i>
                </div>
            @endif
            <div>
                <div class="font-bold text-[#1E3A8A]">{{ $payment->client_name }}</div>
                <div class="text-xs text-[#1E3A8A]/60">{{ $payment->client_id }}</div>
            </div>
        </div>
    </div>

    <!-- Payment Details -->
    <div class="grid grid-cols-2 gap-4">
        <div class="p-4 bg-slate-50 rounded-2xl">
            <div class="text-xs font-bold text-[#1E3A8A]/40 uppercase mb-1">No. Invoice</div>
            <div class="font-bold text-[#1E3A8A]">{{ $payment->payment_number }}</div>
        </div>
        <div class="p-4 bg-slate-50 rounded-2xl">
            <div class="text-xs font-bold text-[#1E3A8A]/40 uppercase mb-1">Nominal</div>
            <div class="font-bold text-[#1E3A8A]">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
        </div>
        <div class="p-4 bg-slate-50 rounded-2xl">
            <div class="text-xs font-bold text-[#1E3A8A]/40 uppercase mb-1">Metode Pembayaran</div>
            <div class="font-bold text-[#1E3A8A]">
                @switch($payment->payment_method)
                    @case('transfer')
                        <i class="fas fa-bank"></i> Transfer Bank
                    @break
                    @case('card')
                        <i class="fas fa-credit-card"></i> Kartu Kredit
                    @break
                    @case('e-wallet')
                        <i class="fas fa-wallet"></i> E-Wallet
                    @break
                    @default
                        {{ ucfirst($payment->payment_method) }}
                @endswitch
            </div>
        </div>
        <div class="p-4 bg-slate-50 rounded-2xl">
            <div class="text-xs font-bold text-[#1E3A8A]/40 uppercase mb-1">Tanggal Pembayaran</div>
            <div class="font-bold text-[#1E3A8A]">{{ $payment->payment_date->format('d M Y H:i') }}</div>
        </div>
    </div>

    <!-- Project Info -->
    <div>
        <h4 class="text-sm font-bold text-[#1E3A8A] mb-3">Informasi Proyek</h4>
        <div class="p-4 bg-slate-50 rounded-2xl">
            <div class="font-bold text-[#1E3A8A] mb-2">{{ $payment->project->title ?? 'N/A' }}</div>
            <div class="text-sm text-[#1E3A8A]/60">{{ $payment->description }}</div>
        </div>
    </div>

    <!-- Notes from UMKM -->
    @if($payment->notes_from_umkm)
    <div>
        <h4 class="text-sm font-bold text-[#1E3A8A] mb-3">Catatan dari UMKM</h4>
        <div class="p-4 bg-amber-50 rounded-2xl border border-amber-200">
            <p class="text-sm text-[#1E3A8A]">{{ $payment->notes_from_umkm }}</p>
        </div>
    </div>
    @endif

    <!-- Proof File -->
    <div>
        <h4 class="text-sm font-bold text-[#1E3A8A] mb-3">Bukti Pembayaran</h4>
        @if($payment->proof_file_url)
            <div class="bg-slate-50 rounded-2xl p-4">
                @if(in_array($payment->proof_file_type, ['jpg', 'jpeg', 'png', 'gif']))
                    <img src="{{ $payment->proof_file_url }}" alt="Bukti Pembayaran" class="w-full rounded-xl max-h-80 object-cover">
                    <a href="{{ $payment->proof_file_url }}" target="_blank" class="mt-4 inline-block w-full text-center py-2 px-4 bg-blue-500 text-white rounded-xl font-bold hover:bg-blue-600 transition-all">
                        <i class="fas fa-external-link-alt"></i> Buka dalam Tab Baru
                    </a>
                @elseif($payment->proof_file_type === 'pdf')
                    <div class="text-center py-8">
                        <i class="fas fa-file-pdf text-5xl text-red-500 mb-4"></i>
                        <p class="font-bold text-[#1E3A8A] mb-2">File PDF</p>
                        <a href="{{ $payment->proof_file_url }}" target="_blank" class="inline-block py-2 px-4 bg-blue-500 text-white rounded-xl font-bold hover:bg-blue-600 transition-all">
                            <i class="fas fa-download"></i> Download PDF
                        </a>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-file text-5xl text-slate-400 mb-4"></i>
                        <p class="font-bold text-[#1E3A8A] mb-2">File {{ strtoupper($payment->proof_file_type) }}</p>
                        <a href="{{ $payment->proof_file_url }}" target="_blank" class="inline-block py-2 px-4 bg-blue-500 text-white rounded-xl font-bold hover:bg-blue-600 transition-all">
                            <i class="fas fa-download"></i> Download File
                        </a>
                    </div>
                @endif
            </div>
        @else
            <div class="text-center py-8 bg-slate-50 rounded-2xl">
                <i class="fas fa-exclamation-triangle text-3xl text-amber-500 mb-2"></i>
                <p class="text-sm text-[#1E3A8A]/60">Bukti pembayaran tidak ditemukan</p>
            </div>
        @endif
    </div>

    <!-- Status & Verification Info -->
    <div>
        <h4 class="text-sm font-bold text-[#1E3A8A] mb-3">Status Verifikasi</h4>
        <div class="grid grid-cols-2 gap-4">
            <div class="p-4 bg-slate-50 rounded-2xl">
                <div class="text-xs font-bold text-[#1E3A8A]/40 uppercase mb-1">Status</div>
                <div class="font-bold text-[#1E3A8A]">
                    @if($payment->status === 'paid' && !$payment->verified_at)
                        <span class="px-2 py-1 bg-amber-50 text-amber-600 rounded-lg text-xs font-bold">Menunggu Verifikasi</span>
                    @elseif($payment->verified_at)
                        <span class="px-2 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-bold">Terverifikasi</span>
                    @elseif($payment->status === 'failed')
                        <span class="px-2 py-1 bg-red-50 text-red-600 rounded-lg text-xs font-bold">Ditolak</span>
                    @endif
                </div>
            </div>
            @if($payment->verified_at)
            <div class="p-4 bg-emerald-50 rounded-2xl">
                <div class="text-xs font-bold text-emerald-600/40 uppercase mb-1">Diverifikasi Pada</div>
                <div class="font-bold text-emerald-600">{{ $payment->verified_at->format('d M Y H:i') }}</div>
            </div>
            @endif
            @if($payment->rejected_at)
            <div class="p-4 bg-red-50 rounded-2xl col-span-2">
                <div class="text-xs font-bold text-red-600/40 uppercase mb-1">Ditolak Pada</div>
                <div class="font-bold text-red-600">{{ $payment->rejected_at->format('d M Y H:i') }}</div>
            </div>
            @endif
            @if($payment->rejection_reason)
            <div class="p-4 bg-red-50 rounded-2xl col-span-2">
                <div class="text-xs font-bold text-red-600/40 uppercase mb-2">Alasan Penolakan</div>
                <p class="text-red-600 text-sm">{{ $payment->rejection_reason }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
