# 🚀 Payment System - Quick Start Guide

## Ringkas Fitur Payment

Fitur Payment System memungkinkan UMKM untuk:
1. **Membuat Invoice Pembayaran** setelah proyek selesai
2. **Mengunggah Bukti Pembayaran** dengan file bukti transfer
3. **Melacak Status Pembayaran** real-time
4. Admin dapat **Memverifikasi atau Menolak** pembayaran

---

## 🎯 Workflow Penggunaan

### Untuk UMKM

#### 1️⃣ Proyek Selesai → Buat Invoice

```
Halaman: Dashboard UMKM → Progress Proyek
- Ketika proyek sudah "completed"
- Klik tombol "Buat Invoice Pembayaran" (hijau)
- Sistem auto-generate invoice dengan nomor unik
- Redirect ke halaman detail pembayaran
```

#### 2️⃣ Upload Bukti Pembayaran

```
Halaman: /pembayaran/{paymentId}

Form isian:
✓ Tanggal Pembayaran (pilih kalender)
✓ Metode Pembayaran (pilih: Transfer Bank, Kartu Kredit, E-Wallet, Lainnya)
✓ File Bukti (drag & drop atau klik)
  - Format: PDF, JPG, PNG, GIF
  - Max: 10MB
  - Contoh: Screenshot transfer, struk pembayaran, dll
✓ Catatan (optional)

Klik "Upload Bukti Pembayaran"
→ File langsung di-upload ke Cloudinary
→ Status berubah menjadi "Terbayar"
→ Tunggu verifikasi admin
```

#### 3️⃣ Pantau Status Pembayaran

```
Halaman: /pembayaran (daftar semua pembayaran)

Status yang mungkin:
⏳ Menunggu      → Blm upload bukti
✅ Terbayar       → Bukti sudah upload, menunggu verifikasi admin
✓ Terverifikasi   → Admin sudah verifikasi, pembayaran OK!
❌ Ditolak        → Admin tolak, lihat alasan dan kirim ulang
— Dibatalkan      → UMKM yang membatalkan
```

---

### Untuk Admin

#### Verifikasi Pembayaran

```
Halaman: Dashboard Admin → Manajemen Pembayaran

1. Lihat daftar pembayaran dengan status "Terbayar"
2. Klik "Lihat Detail"
3. Review bukti pembayaran (PDF/Gambar)
4. Verifikasi:
   - Jumlah sesuai? ✓
   - Bukti jelas? ✓
   - Metode pembayaran valid? ✓
5. Klik "Verifikasi Pembayaran"
   → Status berubah menjadi "Terverifikasi"
   → Proyek otomatis "completed"
   → Email dikirim ke UMKM

OR

Tolak jika ada masalah:
   → Input alasan penolakan
   → Klik "Tolak Pembayaran"
   → UMKM bisa kirim ulang bukti
```

---

## 📊 Status Payment

| Status | Deskripsi | Aksi |
|--------|-----------|------|
| **Pending** | Invoice baru dibuat, belum upload bukti | Upload bukti atau batalkan |
| **Paid** | Bukti sudah upload, waiting admin review | Tunggu verifikasi (max 24jam) |
| **Verified** | Admin sudah verifikasi, pembayaran OK | Selesai ✓ |
| **Failed** | Admin tolak pembayaran | Lihat alasan, upload ulang |
| **Cancelled** | UMKM batalkan pembayaran | Buat invoice baru |

---

## 📱 Halaman & URL

### UMKM

| URL | Deskripsi |
|-----|-----------|
| `/pembayaran` | Daftar semua pembayaran (dengan pagination) |
| `/pembayaran/{id}` | Detail pembayaran + form upload bukti |
| `/proyek/{id}/pembayaran/buat` (POST) | Generate invoice baru |
| `/pembayaran/{id}/bukti-upload` (POST) | Upload bukti |

### Admin

| URL | Deskripsi |
|-----|-----------|
| `/admin/pembayaran/{id}/verifikasi` (POST) | Verifikasi pembayaran |
| `/admin/pembayaran/{id}/tolak` (POST) | Tolak pembayaran |

---

## 🗂️ Data Yang Disimpan

### Setiap Payment Record Berisi:

```
📋 Invoice Information
├─ No Invoice: PAY-20260425-1234 (unique)
├─ Tanggal dibuat: 2026-04-25
├─ Jumlah: Rp 5.000.000
├─ Mata uang: IDR

👤 UMKM Information
├─ Nama: PT. ABC Jaya
├─ Avatar: [profile image]

📦 Proyek Information
├─ Project ID: linked to project
├─ Deskripsi: "Pembayaran untuk proyek: [nama proyek]"

💳 Payment Details
├─ Metode: transfer/card/e-wallet/other
├─ Tanggal pembayaran: 2026-04-25
├─ File bukti: [URL ke Cloudinary]
├─ Catatan UMKM: optional notes

✅ Verification
├─ Status: pending/paid/verified/failed/cancelled
├─ Verified at: timestamp
├─ Verified by: admin ID
├─ Rejection reason: jika ditolak
```

---

## 🔒 Keamanan

- ✅ Hanya UMKM pemilik proyek yang bisa upload bukti
- ✅ Hanya Admin yang bisa verifikasi/tolak
- ✅ File di-upload ke Cloudinary (secure cloud storage)
- ✅ Setiap action ter-log di laravel.log
- ✅ Tidak ada duplikasi pembayaran

---

## 💡 Best Practices

### Untuk UMKM

1. **Sebelum Upload:**
   - Pastikan bukti pembayaran jelas & lengkap
   - Include nama pengirim, tanggal, nominal
   - File minimal 2MB untuk kualitas baik

2. **Format Bukti yang Diterima:**
   - Screenshot transfer bank ✓
   - Struk ATM/EDC ✓
   - Invoice e-wallet ✓
   - PDF nota pembayaran ✓
   - Foto nota fisik (jelas) ✓

3. **Jika Ditolak:**
   - Baca alasan penolakan dengan cermat
   - Upload bukti yang benar
   - Hub admin jika ada pertanyaan

### Untuk Admin

1. **Verifikasi:**
   - Cek nominal = budget proyek
   - Cek tanggal pembayaran reasonable
   - Cek bukti jelas & profesional
   - Verifikasi dalam 1x24 jam

2. **Jika Ada Masalah:**
   - Jelaskan alasan penolakan dengan jelas
   - Give UMKM kesempatan upload ulang
   - Hubungi UMKM jika perlu clarification

---

## 🐛 Troubleshooting

### "Upload gagal - File terlalu besar"
→ File max 10MB. Compress gambar atau gunakan PDF

### "Pembayaran ini sudah ada"
→ Sudah ada invoice pending/paid. Batalkan yang lama atau upload bukti

### "Akses ditolak"
→ Pembayaran bukan punya Anda atau Anda bukan admin

### "Invoice tidak generate"
→ Pastikan proyek sudah status "completed"

---

## 📞 Support

- Admin Dashboard: `/dashboard/admin`
- Log errors: `storage/logs/laravel.log`
- Contact: admin@konekin.id

---

**Fitur dibuat:** 25 April 2026
**Last updated:** 25 April 2026
