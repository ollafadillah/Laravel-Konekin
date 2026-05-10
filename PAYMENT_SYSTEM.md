# 💳 Payment System - Dokumentasi

## Overview
Payment System adalah fitur untuk mengelola pembayaran proyek setelah selesai. Fitur ini memungkinkan UMKM untuk membuat invoice pembayaran, mengunggah bukti pembayaran, dan admin untuk memverifikasi pembayaran.

## 📋 Workflow

```
Proyek Selesai
    ↓
UMKM Membuat Invoice → Payment (Status: pending)
    ↓
UMKM Upload Bukti Pembayaran → Payment (Status: paid)
    ↓
Admin Verifikasi → Payment (Status: paid, verified)
    ↓
Proyek Final (Status: completed)
```

## 🏗️ Struktur Database

### Collection: payments

```javascript
{
  _id: ObjectId,
  project_id: String,           // ID proyek
  client_id: String,            // ID UMKM (pembayar)
  client_name: String,          // Nama UMKM
  client_avatar: String,        // Avatar UMKM
  amount: Decimal,              // Jumlah pembayaran
  currency: String,             // Mata uang (IDR, USD, etc)
  payment_number: String,       // Nomor invoice unik (PAY-20260425-1234)
  description: String,          // Deskripsi pembayaran
  status: String,               // pending, paid, failed, cancelled
  payment_method: String,       // transfer, card, e-wallet, other
  payment_date: Date,           // Tanggal pembayaran dilakukan
  proof_file_url: String,       // URL bukti pembayaran di Cloudinary
  proof_file_type: String,      // Tipe file (pdf, jpg, png, etc)
  notes_from_umkm: String,      // Catatan dari UMKM
  verified_at: Date,            // Tanggal verifikasi admin
  verified_by: String,          // ID admin yang verifikasi
  rejection_reason: String,     // Alasan penolakan (jika ditolak)
  rejected_at: Date,            // Tanggal penolakan
  created_at: Date,
  updated_at: Date
}
```

## 📁 File Structure

```
app/
├── Models/
│   └── Payment.php                 # Model Payment
├── Http/Controllers/
│   └── PaymentController.php       # Controller untuk payment

database/
└── migrations/
    └── 2026_04_25_093000_create_payments_table.php

routes/
└── web.php                         # Routes untuk payment

resources/views/payments/
├── index.blade.php                 # Daftar pembayaran
└── show.blade.php                  # Detail pembayaran & upload bukti
```

## 🔌 API Endpoints

### Untuk UMKM

| Method | Route | Deskripsi |
|--------|-------|-----------|
| GET | `/pembayaran` | Daftar pembayaran UMKM |
| POST | `/proyek/{projectId}/pembayaran/buat` | Buat invoice pembayaran |
| GET | `/pembayaran/{paymentId}` | Detail pembayaran |
| POST | `/pembayaran/{paymentId}/bukti-upload` | Upload bukti pembayaran |
| POST | `/pembayaran/{paymentId}/batalkan` | Batalkan pembayaran |

### Untuk Admin

| Method | Route | Deskripsi |
|--------|-------|-----------|
| POST | `/admin/pembayaran/{paymentId}/verifikasi` | Verifikasi pembayaran |
| POST | `/admin/pembayaran/{paymentId}/tolak` | Tolak pembayaran |

## 💻 Cara Menggunakan

### 1. Generate Payment Invoice

Ketika proyek selesai, UMKM bisa membuat invoice:

```php
// Controller: PaymentController@generatePayment
POST /proyek/{projectId}/pembayaran/buat

// Proses:
- Cek apakah user adalah UMKM
- Cek apakah proyek milik user
- Cek apakah sudah ada pembayaran pending/paid
- Buat payment record baru
- Redirect ke detail pembayaran
```

### 2. Upload Bukti Pembayaran

UMKM mengunggah bukti pembayaran:

```php
// Controller: PaymentController@uploadProof
POST /pembayaran/{paymentId}/bukti-upload

Form Data:
- proof_file (required): File bukti (PDF, JPG, PNG, GIF, max 10MB)
- payment_method (required): transfer, card, e-wallet, other
- payment_date (required): Tanggal pembayaran
- notes (optional): Catatan tambahan

Proses:
- Validasi file dan form data
- Upload file ke Cloudinary
- Update payment status menjadi "paid"
- Send notification ke admin
```

### 3. Admin Verifikasi

Admin memverifikasi pembayaran:

```php
// Controller: PaymentController@verify
POST /admin/pembayaran/{paymentId}/verifikasi

Proses:
- Set payment status ke "paid"
- Set verified_at dan verified_by
- Update project status ke "completed"
- Send notification ke UMKM
```

### 4. Admin Tolak

Admin menolak pembayaran:

```php
// Controller: PaymentController@reject
POST /admin/pembayaran/{paymentId}/tolak

Form Data:
- rejection_reason (required): Alasan penolakan

Proses:
- Set payment status ke "failed"
- Simpan rejection reason
- Set rejected_at
- Send notification ke UMKM
```

## 📦 Model Methods

```php
$payment = Payment::find($id);

// Helper methods
$payment->isPending();       // Check if status = pending
$payment->isPaid();          // Check if status = paid
$payment->isFailed();        // Check if status = failed
$payment->isCancelled();     // Check if status = cancelled

// Generate unique payment number
$number = $payment->generatePaymentNumber();  // PAY-20260425-1234
```

## 🎨 View Components

### Daftar Pembayaran (index.blade.php)

Menampilkan:
- Tabel daftar pembayaran
- Informasi: No Invoice, Proyek, Jumlah, Tanggal, Status
- Filter berdasarkan status
- Pagination

### Detail Pembayaran (show.blade.php)

Menampilkan:
- Invoice pembayaran (format resmi)
- Informasi proyek
- Form upload bukti pembayaran (jika status = pending)
- Status pembayaran dengan detail
- Sidebar ringkasan pembayaran

## 🔐 Authorization

```php
// UMKM hanya bisa melihat pembayaran mereka sendiri
if ((string)$payment->client_id !== (string)auth()->id()) {
    abort(403, 'Unauthorized');
}

// Admin bisa verifikasi dan tolak
if (!auth()->user()->isAdmin()) {
    abort(403, 'Unauthorized');
}
```

## 📧 Notifications

Kirim email/notifikasi ke:
1. **UMKM**: Ketika invoice dibuat → Instruksi pembayaran
2. **Admin**: Ketika bukti diunggah → Review pembayaran
3. **UMKM**: Ketika pembayaran diverifikasi → Konfirmasi pembayaran
4. **UMKM**: Ketika pembayaran ditolak → Alasan dan instruksi

## 🗂️ Contoh Kode

### Generate Payment

```php
$project = Project::find($projectId);

$payment = Payment::create([
    'project_id' => $projectId,
    'client_id' => $project->client_id,
    'client_name' => $project->client_name,
    'client_avatar' => $project->client_avatar,
    'amount' => convertToNumber($project->budget),  // Rp 5.000.000 → 5000000
    'currency' => 'IDR',
    'description' => "Pembayaran untuk proyek: {$project->title}",
    'status' => 'pending',
]);

$payment->payment_number = $payment->generatePaymentNumber();
$payment->save();
```

### Upload Proof

```php
$proofFile = $request->file('proof_file');
$proofUrl = $cloudinary->upload($proofFile, [
    'folder' => 'konekin/payments/proofs',
    'resource_type' => 'auto',
]);

$payment->update([
    'proof_file_url' => $proofUrl,
    'proof_file_type' => $proofFile->getClientOriginalExtension(),
    'payment_method' => $request->payment_method,
    'notes_from_umkm' => $request->notes,
    'payment_date' => $request->payment_date,
    'status' => 'paid',
]);
```

## 🐛 Error Handling

### Payment not found
```
HTTP 404 - Payment tidak ditemukan
```

### Unauthorized access
```
HTTP 403 - Anda tidak memiliki akses ke pembayaran ini
```

### Invalid file
```
HTTP 422 - File bukti pembayaran gagal di-upload
Error message: "Bukti pembayaran gagal di-upload: [error details]"
```

### Duplicate payment
```
Pembayaran untuk proyek ini sudah ada
```

## 🚀 Future Enhancements

- [ ] Integration dengan payment gateway (Midtrans, Stripe, etc)
- [ ] Automatic payment reminders
- [ ] Payment receipts generation (PDF)
- [ ] Invoice template customization
- [ ] Multi-currency support
- [ ] Refund management
- [ ] Payment reports & analytics
- [ ] Webhook untuk payment confirmation

## 📝 Notes

- Semua file bukti disimpan di Cloudinary dengan folder `konekin/payments/proofs`
- Payment number format: `PAY-YYYYMMDD-XXXX` (unik setiap waktu)
- Status flow: pending → paid → verified (oleh admin)
- Jika ditolak: paid → failed
- Jika dibatalkan: pending → cancelled
