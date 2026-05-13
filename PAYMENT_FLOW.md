# Payment And Escrow Flow

Dokumen ini adalah referensi tunggal untuk alur payment, escrow, revisi, dispute, dan pencairan dana di Konekin.

## Prinsip Utama

- UMKM tidak membayar langsung ke creative worker.
- Setelah draft/progress 100%, UMKM wajib membayar escrow.
- Dana ditahan platform sampai hasil akhir disetujui atau dispute diputuskan admin.
- Platform mengambil fee 15%.
- Creative worker menerima net amount setelah admin release.
- Jika ada masalah, UMKM bisa meminta revisi atau membuka dispute.

## Aktor

- UMKM: pemilik proyek dan pembayar.
- Creative worker: pelamar, pekerja proyek, penerima dana.
- Admin/mediator: verifikasi pembayaran, menyelesaikan dispute, dan release/refund dana.

## Alur Utama

```text
1. UMKM membuat proyek
2. Creative worker apply dan upload proposal
3. UMKM memilih satu creative worker
4. Creative worker mengirim update progress sampai 100%
5. UMKM generate VA escrow
6. UMKM transfer dan upload bukti pembayaran
7. Admin verifikasi bukti
8. Dana masuk escrow/held
9. UMKM review hasil
10. UMKM approve, minta revisi, atau dispute
11. Admin release dana atau refund sesuai keputusan
12. UMKM memberi rating setelah proyek selesai
```

## Status Project

| Status | Arti |
| --- | --- |
| `open` | Proyek baru, belum ada creative worker dipilih |
| `applied` | Sudah ada proposal masuk |
| `hired` | UMKM sudah memilih creative worker |
| `in_progress` | Creative worker sedang mengerjakan proyek |
| `awaiting_payment` | Progress 100%, UMKM perlu membuat pembayaran |
| `payment_pending` | VA/payment sudah dibuat atau bukti menunggu verifikasi |
| `ready_for_review` | Dana sudah held, UMKM bisa review hasil |
| `revision` | UMKM meminta revisi |
| `disputed` | Dispute aktif, admin/mediator menentukan keputusan |
| `pending_admin_approval` | UMKM sudah approve hasil, admin menyiapkan release dana |
| `payment_refunded` | Dana dikembalikan ke UMKM |
| `completed` | Proyek selesai dan dana sudah diproses |

## Status Payment

| Status | Arti |
| --- | --- |
| `pending` | VA/payment dibuat, UMKM belum upload bukti atau belum selesai bayar |
| `paid` | Bukti sudah diupload dan menunggu/verifikasi admin |
| `verified` | Admin menerima bukti pembayaran |
| `failed` | Admin menolak bukti pembayaran |
| `cancelled` | Payment dibatalkan sebelum selesai |

## Status Escrow

| Status | Arti |
| --- | --- |
| `unpaid` | Belum ada dana masuk |
| `pending` | Bukti pembayaran menunggu verifikasi |
| `held` | Dana ditahan platform |
| `disputed` | Dana dibekukan karena dispute |
| `releasing` | Proses pencairan ke creative worker |
| `released` | Dana sudah dicairkan |
| `refunded` | Dana dikembalikan ke UMKM |

## Fee Platform

```text
gross_amount = budget proyek
platform_fee = 15% dari gross_amount
net_amount = gross_amount - platform_fee
```

Contoh:

```text
Budget proyek: Rp 1.000.000
Fee platform: Rp 150.000
Creative worker menerima: Rp 850.000
```

## Revisi

UMKM dapat meminta revisi setelah dana berada di escrow dan hasil sudah siap direview.

Rules:

- Revisi harus punya alasan yang jelas.
- Dana tetap ditahan platform.
- Creative worker mengirim update progress/revisi baru.
- UMKM kembali review setelah revisi selesai.

## Dispute Resolution

Dispute digunakan saat UMKM dan creative worker tidak sepakat.

Contoh:

- UMKM merasa revisi tidak sesuai brief.
- Creative worker merasa pekerjaan sudah sesuai scope.
- Bukti atau hasil kerja diperdebatkan.

Admin/mediator dapat:

- Release dana ke creative worker.
- Refund dana ke UMKM.
- Meminta bukti/tindakan tambahan sebelum keputusan.

Selama dispute:

- Dana tidak boleh dicairkan otomatis.
- Project berada di status `disputed`.
- Escrow berada di status `disputed`.

## Route Web Penting

UMKM:

```text
GET  /progress-proyek
POST /progress-proyek/{id}/approve/{applicationId}
POST /progress-proyek/{id}/approve-completion
POST /progress-proyek/{id}/revision
POST /progress-proyek/{id}/dispute
POST /proyek/{projectId}/pembayaran/buat
GET  /pembayaran/{paymentId}
POST /pembayaran/{paymentId}/bukti-upload
POST /pembayaran/{paymentId}/batalkan
```

Creative worker:

```text
GET  /progress-proyek-kreator
POST /progress-proyek-kreator/{id}
GET  /penghasilan
```

Admin:

```text
GET  /admin/pembayaran
POST /admin/pembayaran/{paymentId}/verifikasi
POST /admin/pembayaran/{paymentId}/tolak
GET  /admin/escrow
POST /admin/escrow/{id}/release
POST /admin/disputes/{id}/resolve
```

Proposal:

```text
GET /proposal/{applicationId}/preview
GET /proposal/{applicationId}/download
```

## API Penting

```text
GET  /api/creative/escrow
GET  /api/creative/escrow/{id}
GET  /api/creative/earnings
POST /api/creative/projects/{id}/progress
GET  /api/admin/projects/pending-approval
POST /api/admin/projects/{id}/approve-completion
POST /api/admin/projects/{id}/reject-completion
```

## File Utama

```text
app/Models/Payment.php
app/Models/EscrowTransaction.php
app/Models/Project.php
app/Http/Controllers/PaymentController.php
app/Http/Controllers/ProjectController.php
app/Http/Controllers/ProjectApprovalController.php
app/Http/Controllers/AdminEscrowController.php
app/Http/Controllers/CreativeEscrowController.php
app/Jobs/ProcessDisbursement.php
resources/views/payments/
resources/views/projects/progress.blade.php
resources/views/admin/escrow/
resources/views/admin/project-approvals/
```

## Operational Notes

- Queue worker harus berjalan agar job disbursement dan notification diproses.
- Integrasi pencairan real masih perlu disambungkan ke payment provider/disbursement API yang dipilih.
- Semua upload bukti dan proposal harus divalidasi ukuran, MIME, dan authorization-nya.
- Jika menambah status baru, update tabel status di dokumen ini dan helper status di controller/model terkait.
