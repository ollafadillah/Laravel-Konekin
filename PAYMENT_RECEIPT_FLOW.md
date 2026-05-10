# 💳 Payment Receipt Verification Flow

## 🔄 Alur Pembayaran dengan Resi (Upfront)

### Stage 1: UMKM Approve Creative Worker
```
1. UMKM lihat lamaran kreator
2. Klik "Approve" → Redirect ke /proyek/{id}/resi-pembayaran
3. Form upload resi pembayaran:
   - Foto/scan resi bank transfer
   - Nama bank / platform pembayaran
   - Tanggal pembayaran
   - Metode pembayaran
   - Catatan tambahan (opsional)
4. Submit → Create EscrowTransaction (status: pending)
   - Project status tetap 'hired'
   - Escrow status = 'pending' (menunggu verifikasi admin)
```

### Stage 2: Admin Verifikasi Resi
**Halaman: `/admin/verifikasi-resi`**

```
1. Admin lihat daftar resi menunggu verifikasi
   - Stat: Menunggu verifikasi, Total nominal, Verifikasi hari ini
2. Admin review:
   - Detail pembayaran (bank, tanggal, metode)
   - Foto resi (clickable untuk full size)
   - Catatan dari UMKM
3. Admin pilih:
   ✅ TERIMA → Escrow status 'pending' → 'held'
              Project status 'hired' → 'in_progress'
              Creative worker mulai bekerja
              
   ❌ TOLAK → Escrow status 'pending' → 'rejected'
             Project reset ke 'hired'
             UMKM harus upload resi ulang
```

### Stage 3: Creative Worker Bekerja
```
1. Creative worker update progress
2. Progress reach 100%
3. Project dalam status 'in_progress' dengan escrow 'held'
```

### Stage 4: Admin Approve & Cairkan Dana
**Halaman: `/admin/escrow` (Tab: Pending Approval)**

```
1. Admin lihat tab "Pending Approval"
   - Stat: Menunggu Approval, Total Nilai Pending
   - Table: Semua escrow dengan status 'held'
   
2. Admin review:
   - Project title & UMKM name
   - Creative worker name
   - Current progress
   - Nominal & fee breakdown
   
3. Admin klik "Approve & Cairkan"
   - Escrow status 'held' → 'releasing' → 'released'
   - Project status 'in_progress' → 'completed'
   - ProcessDisbursement job dijalankan
   - Creative worker dapat notifikasi (EscrowFundsReleased)
```

### Stage 5: UMKM Rating Creative Worker
**Halaman: `/projects/progress`**

```
1. Project status sudah 'completed'
2. UMKM lihat project di Progress tab
3. Klik "Berikan Rating"
   - Rating form muncul (1-5 bintang + comment)
   - Submit → Rating tersimpan
   - Project dipindahkan ke history
```

---

## 📋 Status Transitions

### EscrowTransaction Status
- `pending` → Resi uploaded, menunggu admin verify
- `held` → Resi terverifikasi, dana aman di escrow
- `releasing` → Admin approve pencairan, job sedang process
- `released` → Dana sudah ditransfer ke creative worker ✅
- `rejected` → Resi ditolak admin, harus upload ulang

### Project Status
- `hired` → UMKM approve lamaran, tunggu resi payment
- `in_progress` → Admin verify resi, creative worker bisa mulai kerjakan
- `completed` → Admin approve pencairan, UMKM bisa rate creative ✅

### Project Escrow Status
- `null` → Belum ada escrow
- `pending` → Resi uploaded, admin belum verify
- `held` → Dana masuk escrow, creative bisa mulai
- `releasing` → Sedang diproses pencairan
- `released` → Dana sudah ditransfer ✅

---

## 🔗 Routes

### UMKM Routes
- `GET /proyek/{id}/resi-pembayaran` → Show receipt upload form
- `POST /proyek/{id}/resi-pembayaran` → Store receipt & create pending escrow

### Admin Routes
- `GET /admin/verifikasi-resi` → List pending verification receipts
- `POST /admin/escrow/{id}/verifikasi-resi` → Verify receipt (approve/reject)
- `GET /admin/escrow` → Tab-based (All Transactions, Pending Approval)
- `POST /admin/escrow/{id}/release` → Approve & process disbursement

### Creative Routes
- `GET /penghasilan` → View earnings dashboard
- `GET /api/creative/escrow` → API list escrows
- `GET /api/creative/earnings` → API earnings summary

---

## 💡 Key Notes

1. **No UMKM Approval for Completion**
   - Unlike the old flow, UMKM does NOT approve completion separately
   - Admin just approves disbursement after creative work is done
   
2. **Rating Unlocked After Disbursement**
   - Rating only available when `project->status === 'completed'`
   - This happens automatically when admin approves disbursement
   
3. **Two Approval Paths**
   - **Payment Receipt Path (Current)**:
     UMKM upload resi → Admin verify → Creative work → Admin approve disbursement
   
   - **Legacy Project Completion Path (Optional)**:
     If using ProjectApprovalController, UMKM explicitly approves completion

