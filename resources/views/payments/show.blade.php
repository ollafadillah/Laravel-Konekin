@extends('layouts.app')

@section('content')
    @php
        $platformFee = (int) ($payment->platform_fee ?? round($payment->amount * 0.15));
        $netAmount = (int) ($payment->net_amount ?? ($payment->amount - $platformFee));
        $statusConfig = match (true) {
            $payment->isPending() => [
                'label' => 'Menunggu Transfer',
                'note' => 'Transfer ke VA lalu upload bukti pembayaran.',
                'icon' => 'fa-clock',
                'chip' => 'bg-amber-50 text-amber-700 border-amber-200',
                'panel' => 'from-amber-50 to-white border-amber-100',
            ],
            $payment->isAwaitingVerification() => [
                'label' => 'Bukti Dikirim',
                'note' => 'Admin sedang memverifikasi bukti pembayaran.',
                'icon' => 'fa-file-circle-check',
                'chip' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'panel' => 'from-emerald-50 to-white border-emerald-100',
            ],
            $payment->isVerified() => [
                'label' => 'Dana Ditahan Escrow',
                'note' => 'Pembayaran valid dan dana aman di platform.',
                'icon' => 'fa-shield-halved',
                'chip' => 'bg-blue-50 text-[#2563EB] border-blue-200',
                'panel' => 'from-blue-50 to-white border-blue-100',
            ],
            $payment->isFailed() => [
                'label' => 'Pembayaran Ditolak',
                'note' => 'Periksa alasan penolakan dan hubungi admin bila perlu.',
                'icon' => 'fa-circle-xmark',
                'chip' => 'bg-red-50 text-red-700 border-red-200',
                'panel' => 'from-red-50 to-white border-red-100',
            ],
            $payment->isCancelled() => [
                'label' => 'Dibatalkan',
                'note' => 'Invoice ini sudah dibatalkan.',
                'icon' => 'fa-ban',
                'chip' => 'bg-slate-100 text-slate-600 border-slate-200',
                'panel' => 'from-slate-50 to-white border-slate-100',
            ],
            default => [
                'label' => 'Invoice',
                'note' => 'Detail pembayaran escrow.',
                'icon' => 'fa-file-invoice',
                'chip' => 'bg-blue-50 text-[#2563EB] border-blue-200',
                'panel' => 'from-blue-50 to-white border-blue-100',
            ],
        };
    @endphp

    <div class="px-4 py-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <x-page-back :href="route('payments.index')" label="Kembali ke Pembayaran" class="mb-6" />

            @if ($errors->any())
                <div class="mb-6 rounded-3xl border border-red-200 bg-red-50 p-5 text-red-700">
                    <p class="font-bold">Ada bagian yang perlu dicek:</p>
                    <ul class="mt-2 list-disc space-y-1 pl-5 text-sm font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 rounded-3xl border border-emerald-200 bg-emerald-50 p-5 text-sm font-bold text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 rounded-3xl border border-red-200 bg-red-50 p-5 text-sm font-bold text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <section class="mb-8 overflow-hidden rounded-[2rem] bg-gradient-to-br {{ $statusConfig['panel'] }} border p-6 shadow-sm md:p-8">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex items-start gap-5">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#1E3A8A] text-white shadow-xl shadow-[#1E3A8A]/20">
                            <i class="fas {{ $statusConfig['icon'] }} text-xl"></i>
                        </div>
                        <div>
                            <div class="mb-3 flex flex-wrap items-center gap-3">
                                <span class="rounded-full border px-4 py-2 text-xs font-black uppercase tracking-[0.18em] {{ $statusConfig['chip'] }}">
                                    {{ $statusConfig['label'] }}
                                </span>
                                <span class="font-mono text-xs font-bold text-[#1E3A8A]/55">{{ $payment->payment_number }}</span>
                            </div>
                            <h1 class="font-display text-3xl font-bold leading-tight text-[#1E3A8A] md:text-4xl">Invoice Escrow</h1>
                            <p class="mt-2 max-w-2xl text-sm font-medium leading-7 text-[#1E3A8A]/65">{{ $statusConfig['note'] }}</p>
                        </div>
                    </div>
                    <div class="rounded-[1.5rem] bg-white/80 p-5 text-left shadow-sm ring-1 ring-[#2563EB]/10 lg:text-right">
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-[#2563EB]">Total Transfer</p>
                        <p class="mt-1 font-display text-3xl font-bold text-[#1E3A8A]">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                        <p class="mt-1 text-xs font-semibold text-[#1E3A8A]/50">{{ optional($payment->created_at)->translatedFormat('d M Y H:i') }}</p>
                    </div>
                </div>
            </section>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-[1fr_360px]">
                <div class="space-y-8">
                    <section class="rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-[#2563EB]/8 md:p-8">
                        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                            <div class="rounded-3xl bg-[#F8FAFC] p-5">
                                <p class="text-[10px] font-black uppercase tracking-[0.18em] text-[#2563EB]">Dari</p>
                                <p class="mt-2 font-bold text-[#1E3A8A]">PT. Konekin Indonesia</p>
                                <p class="text-sm font-medium text-[#1E3A8A]/55">Escrow Platform</p>
                            </div>
                            <div class="rounded-3xl bg-[#F8FAFC] p-5">
                                <p class="text-[10px] font-black uppercase tracking-[0.18em] text-[#2563EB]">Untuk</p>
                                <p class="mt-2 font-bold text-[#1E3A8A]">{{ $payment->client_name }}</p>
                                <p class="text-sm font-medium text-[#1E3A8A]/55">UMKM Pemilik Proyek</p>
                            </div>
                            <div class="rounded-3xl bg-[#EFF6FF] p-5">
                                <p class="text-[10px] font-black uppercase tracking-[0.18em] text-[#2563EB]">Net Kreator</p>
                                <p class="mt-2 font-display text-2xl font-bold text-[#1E3A8A]">Rp {{ number_format($netAmount, 0, ',', '.') }}</p>
                                <p class="text-sm font-medium text-[#1E3A8A]/55">Setelah platform fee</p>
                            </div>
                        </div>

                        @if ($project)
                            <div class="mt-6 rounded-3xl border border-[#2563EB]/10 bg-white p-5">
                                <p class="text-[10px] font-black uppercase tracking-[0.18em] text-[#2563EB]">Informasi Proyek</p>
                                <div class="mt-3 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                    <div>
                                        <h2 class="font-display text-xl font-bold text-[#1E3A8A]">{{ $project->title }}</h2>
                                        <p class="mt-1 text-sm font-medium text-[#1E3A8A]/55">{{ $project->category }} · {{ ucfirst(str_replace('_', ' ', $project->status)) }}</p>
                                    </div>
                                    <a href="{{ route('projects.show', $project->id) }}"
                                        class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#EFF6FF] px-4 py-3 text-xs font-black uppercase tracking-widest text-[#2563EB] transition hover:bg-[#2563EB] hover:text-white">
                                        Lihat Proyek
                                        <i class="fas fa-arrow-up-right-from-square"></i>
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="mt-8 overflow-hidden rounded-3xl border border-[#2563EB]/10">
                            <div class="grid grid-cols-[1fr_auto] bg-[#F8FAFC] px-5 py-4 text-xs font-black uppercase tracking-[0.16em] text-[#1E3A8A]/50">
                                <span>Rincian</span>
                                <span>Jumlah</span>
                            </div>
                            <div class="divide-y divide-[#2563EB]/8">
                                <div class="grid grid-cols-[1fr_auto] gap-4 px-5 py-4 text-sm">
                                    <span class="font-medium text-[#1E3A8A]/75">{{ $payment->description }}</span>
                                    <span class="font-bold text-[#1E3A8A]">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="grid grid-cols-[1fr_auto] gap-4 px-5 py-4 text-sm">
                                    <span class="font-medium text-[#1E3A8A]/65">Platform fee 15%</span>
                                    <span class="font-bold text-[#1E3A8A]/75">Rp {{ number_format($platformFee, 0, ',', '.') }}</span>
                                </div>
                                <div class="grid grid-cols-[1fr_auto] gap-4 px-5 py-4 text-sm">
                                    <span class="font-medium text-[#1E3A8A]/65">Net untuk creative worker</span>
                                    <span class="font-bold text-[#1E3A8A]/75">Rp {{ number_format($netAmount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between bg-[#EFF6FF] px-5 py-5">
                                <span class="text-sm font-black uppercase tracking-[0.16em] text-[#2563EB]">Total Transfer</span>
                                <span class="font-display text-2xl font-bold text-[#1E3A8A]">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </section>

                    @if ($payment->isPending())
                        <section class="rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-[#2563EB]/8 md:p-8">
                            <div class="mb-6 flex items-start gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#EFF6FF] text-[#2563EB]">
                                    <i class="fas fa-building-columns"></i>
                                </div>
                                <div>
                                    <h2 class="font-display text-2xl font-bold text-[#1E3A8A]">Bayar via Virtual Account</h2>
                                    <p class="mt-1 text-sm font-medium leading-7 text-[#1E3A8A]/60">Transfer sesuai total invoice, lalu upload bukti pembayaran agar admin bisa menahan dana di escrow.</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                <div class="rounded-3xl border border-[#BFDBFE] bg-[#EFF6FF] p-5">
                                    <p class="text-[10px] font-black uppercase tracking-[0.18em] text-[#2563EB]">Bank</p>
                                    <p class="mt-2 text-xl font-bold text-[#1E3A8A]">{{ $payment->virtual_account_bank ?? 'BCA' }}</p>
                                </div>
                                <div class="rounded-3xl border border-[#BFDBFE] bg-[#EFF6FF] p-5 md:col-span-1">
                                    <p class="text-[10px] font-black uppercase tracking-[0.18em] text-[#2563EB]">Nomor VA</p>
                                    <div class="mt-2 flex items-center gap-2">
                                        <p id="va-number" class="break-all font-mono text-xl font-bold text-[#1E3A8A]">{{ $payment->virtual_account_number ?: '-' }}</p>
                                        @if($payment->virtual_account_number)
                                            <button type="button" onclick="copyVaNumber()" class="rounded-xl bg-white px-3 py-2 text-xs font-bold text-[#2563EB] shadow-sm ring-1 ring-[#2563EB]/10 hover:bg-[#2563EB] hover:text-white">
                                                Salin
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                <div class="rounded-3xl border border-[#BFDBFE] bg-[#EFF6FF] p-5">
                                    <p class="text-[10px] font-black uppercase tracking-[0.18em] text-[#2563EB]">Batas Transfer</p>
                                    <p class="mt-2 text-xl font-bold text-[#1E3A8A]">{{ optional($payment->payment_due_at)->translatedFormat('d M Y H:i') ?? '-' }}</p>
                                </div>
                            </div>

                            <form action="{{ route('payments.upload-proof', $payment->_id) }}" method="POST" enctype="multipart/form-data" class="mt-8 space-y-5">
                                @csrf
                                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                    <div>
                                        <label for="payment_date" class="mb-2 block text-sm font-bold text-[#1E3A8A]">Tanggal Pembayaran</label>
                                        <input type="date" name="payment_date" id="payment_date" required max="{{ now()->format('Y-m-d') }}" value="{{ old('payment_date') }}"
                                            class="w-full rounded-2xl border border-[#2563EB]/15 bg-[#F8FAFC] px-5 py-4 font-semibold text-[#1E3A8A] outline-none transition focus:border-[#2563EB] focus:bg-white focus:ring-4 focus:ring-[#2563EB]/10">
                                    </div>
                                    <div>
                                        <label for="payment_method" class="mb-2 block text-sm font-bold text-[#1E3A8A]">Metode Pembayaran</label>
                                        <select name="payment_method" id="payment_method" required
                                            class="w-full rounded-2xl border border-[#2563EB]/15 bg-[#F8FAFC] px-5 py-4 font-semibold text-[#1E3A8A] outline-none transition focus:border-[#2563EB] focus:bg-white focus:ring-4 focus:ring-[#2563EB]/10">
                                            <option value="virtual_account" {{ old('payment_method', 'virtual_account') === 'virtual_account' ? 'selected' : '' }}>Virtual Account</option>
                                            <option value="transfer" {{ old('payment_method') === 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                            <option value="e-wallet" {{ old('payment_method') === 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
                                            <option value="other" {{ old('payment_method') === 'other' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label for="proof_file" class="mb-2 block text-sm font-bold text-[#1E3A8A]">Upload Bukti Pembayaran</label>
                                    <div class="rounded-3xl border-2 border-dashed border-[#BFDBFE] bg-[#F8FAFC] p-5 transition hover:border-[#2563EB]">
                                        <input type="file" name="proof_file" id="proof_file" accept=".pdf,.jpg,.jpeg,.png,.gif" onchange="previewPaymentProof(this)" required
                                            class="w-full rounded-2xl bg-white px-4 py-3 text-sm font-semibold text-[#1E3A8A] file:mr-4 file:rounded-xl file:border-0 file:bg-[#EFF6FF] file:px-4 file:py-2 file:text-xs file:font-bold file:text-[#2563EB] hover:file:bg-[#2563EB] hover:file:text-white">
                                        <p class="mt-2 text-xs font-semibold text-[#1E3A8A]/50">PDF, JPG, PNG, atau GIF. Maksimal 10MB.</p>
                                        <div id="proof_preview" class="hidden mt-4 rounded-2xl border border-[#BFDBFE] bg-white p-4">
                                            <img id="proof_preview_image" src="" alt="Preview bukti pembayaran" class="hidden w-full max-h-80 rounded-xl bg-white object-contain">
                                            <div id="proof_preview_file" class="hidden rounded-xl bg-[#EFF6FF] px-4 py-3 text-sm font-bold text-[#1E3A8A]"></div>
                                            <p id="proof_preview_meta" class="mt-3 text-xs font-bold text-[#2563EB]"></p>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label for="notes" class="mb-2 block text-sm font-bold text-[#1E3A8A]">Catatan Tambahan</label>
                                    <textarea name="notes" id="notes" rows="4" class="w-full resize-none rounded-2xl border border-[#2563EB]/15 bg-[#F8FAFC] px-5 py-4 font-medium text-[#1E3A8A] outline-none transition focus:border-[#2563EB] focus:bg-white focus:ring-4 focus:ring-[#2563EB]/10" placeholder="Opsional, misalnya nama rekening pengirim atau keterangan transfer.">{{ old('notes') }}</textarea>
                                </div>

                                <div class="flex flex-col gap-3 sm:flex-row">
                                    <button type="submit" class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl bg-[#1E3A8A] px-6 py-4 text-sm font-bold text-white shadow-xl shadow-[#1E3A8A]/15 transition hover:bg-[#2563EB]">
                                        <i class="fas fa-upload"></i>
                                        Upload Bukti Transfer
                                    </button>
                                    <button type="submit" form="cancel-payment-form" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-6 py-4 text-sm font-bold text-slate-500 transition hover:bg-slate-50"
                                        onclick="return confirm('Apakah Anda yakin ingin membatalkan pembayaran ini?')">
                                        Batalkan
                                    </button>
                                </div>
                            </form>

                            <form id="cancel-payment-form" action="{{ route('payments.cancel', $payment->_id) }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </section>
                    @elseif ($payment->isAwaitingVerification())
                        <section class="rounded-[2rem] border border-emerald-200 bg-emerald-50 p-8">
                            <h3 class="font-display text-2xl font-bold text-emerald-800">Bukti Pembayaran Telah Dikirim</h3>
                            <p class="mt-2 text-sm font-medium leading-7 text-emerald-700">Admin akan memverifikasi pembayaran. Setelah valid, dana masuk escrow platform dan belum dicairkan ke creative worker.</p>
                            @if ($payment->proof_file_url)
                                <a href="{{ $payment->proof_file_url }}" target="_blank" class="mt-5 inline-flex items-center gap-2 rounded-2xl bg-white px-5 py-3 text-sm font-bold text-emerald-700 shadow-sm ring-1 ring-emerald-200 hover:bg-emerald-600 hover:text-white">
                                    <i class="fas fa-eye"></i>
                                    Lihat Bukti Pembayaran
                                </a>
                            @endif
                        </section>
                    @elseif ($payment->isVerified())
                        <section class="rounded-[2rem] border border-blue-200 bg-blue-50 p-8">
                            <h3 class="font-display text-2xl font-bold text-[#1E3A8A]">Dana Sudah Ditahan Platform</h3>
                            <p class="mt-2 text-sm font-medium leading-7 text-[#2563EB]">Pembayaran sudah diverifikasi admin. Dana tetap tertahan sampai UMKM menyetujui hasil akhir atau admin menyelesaikan dispute.</p>
                        </section>
                    @elseif ($payment->isFailed())
                        <section class="rounded-[2rem] border border-red-200 bg-red-50 p-8">
                            <h3 class="font-display text-2xl font-bold text-red-800">Pembayaran Ditolak</h3>
                            <p class="mt-2 text-sm font-medium leading-7 text-red-700">{{ $payment->rejection_reason ?: 'Silakan buat ulang pembayaran atau hubungi admin.' }}</p>
                        </section>
                    @endif
                </div>

                <aside class="h-fit rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-[#2563EB]/8 lg:sticky lg:top-28">
                    <h3 class="font-display text-xl font-bold text-[#1E3A8A]">Ringkasan Pembayaran</h3>
                    <div class="mt-5 space-y-4 text-sm">
                        <div class="rounded-2xl bg-[#F8FAFC] p-4">
                            <p class="text-[10px] font-black uppercase tracking-[0.18em] text-[#1E3A8A]/45">No. Invoice</p>
                            <p class="mt-1 break-all font-mono font-bold text-[#1E3A8A]">{{ $payment->payment_number }}</p>
                        </div>
                        <div class="rounded-2xl bg-[#EFF6FF] p-4">
                            <p class="text-[10px] font-black uppercase tracking-[0.18em] text-[#2563EB]">Total Transfer</p>
                            <p class="mt-1 font-display text-2xl font-bold text-[#1E3A8A]">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="rounded-2xl border border-[#2563EB]/10 p-4">
                                <p class="text-[10px] font-black uppercase tracking-widest text-[#1E3A8A]/45">Fee</p>
                                <p class="mt-1 font-bold text-[#1E3A8A]">Rp {{ number_format($platformFee, 0, ',', '.') }}</p>
                            </div>
                            <div class="rounded-2xl border border-[#2563EB]/10 p-4">
                                <p class="text-[10px] font-black uppercase tracking-widest text-[#1E3A8A]/45">Net</p>
                                <p class="mt-1 font-bold text-[#1E3A8A]">Rp {{ number_format($netAmount, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 rounded-3xl border border-amber-200 bg-amber-50 p-5">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-circle-info mt-1 text-amber-600"></i>
                            <div>
                                <p class="text-sm font-bold text-amber-800">Dana aman di escrow</p>
                                <p class="mt-1 text-xs font-medium leading-6 text-amber-700">Pembayaran ini bukan pencairan langsung. Dana ditahan platform sampai hasil proyek disetujui atau dispute selesai.</p>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    <script>
        function formatPaymentProofSize(bytes) {
            if (!bytes) {
                return '0 KB';
            }

            const units = ['B', 'KB', 'MB', 'GB'];
            const index = Math.min(Math.floor(Math.log(bytes) / Math.log(1024)), units.length - 1);
            return `${(bytes / Math.pow(1024, index)).toFixed(index === 0 ? 0 : 1)} ${units[index]}`;
        }

        function copyVaNumber() {
            const number = document.getElementById('va-number')?.textContent?.trim();
            if (!number || number === '-') {
                return;
            }

            navigator.clipboard?.writeText(number);
        }

        function previewPaymentProof(input) {
            const file = input.files && input.files[0];
            const preview = document.getElementById('proof_preview');
            const image = document.getElementById('proof_preview_image');
            const fallback = document.getElementById('proof_preview_file');
            const meta = document.getElementById('proof_preview_meta');

            image.classList.add('hidden');
            fallback.classList.add('hidden');

            if (!file) {
                preview.classList.add('hidden');
                image.src = '';
                fallback.textContent = '';
                meta.textContent = '';
                return;
            }

            preview.classList.remove('hidden');
            meta.textContent = `${file.name} - ${formatPaymentProofSize(file.size)}`;

            if (file.type.startsWith('image/')) {
                image.src = URL.createObjectURL(file);
                image.classList.remove('hidden');
                return;
            }

            fallback.textContent = `${file.name} siap diupload`;
            fallback.classList.remove('hidden');
        }
    </script>
@endsection
