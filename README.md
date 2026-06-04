# Konekin
Solusi Platform Digital (KonekIN) untuk Menghubungkan Creative Workers dan UMKM Menggunakan Algoritma Clustering dan Content-Based Filtering Berbasis Web dan Mobile.
Konekin adalah platform kolaborasi antara UMKM dan creative worker. UMKM dapat mempublikasikan proyek, menerima proposal, memilih creative worker, memantau progress, melakukan pembayaran escrow, meminta revisi, membuka dispute, dan memberi rating setelah proyek selesai.

## Ringkasan Fitur

- Autentikasi role UMKM, creative worker, dan admin.
- Dashboard UMKM, creative worker, dan admin.
- Publikasi proyek dengan media referensi.
- Apply project dengan proposal PDF/DOC/DOCX/PPT/PPTX/ZIP.
- Preview dan download proposal melalui route Laravel yang terproteksi.
- Progress proyek dari creative worker dengan media bukti.
- Escrow payment: VA, upload bukti transfer, verifikasi admin, fee platform 15%, dan pencairan ke creative worker.
- Revision dan dispute resolution.
- Rating creative worker setelah proyek selesai.
- Rekomendasi creative worker berbasis ML service Flask.

## Stack

- Laravel 13, PHP 8.3
- MongoDB Laravel driver
- Vite dan Tailwind CSS
- Cloudinary untuk upload media/dokumen
- Flask ML service untuk rekomendasi KMeans + TF-IDF
- Docker Compose untuk development dan deployment rootless

## Struktur Penting

```text
app/                  Laravel models, controllers, jobs, notifications, services
resources/views/      Blade UI
routes/               Web/API routes
docker/               PHP, Nginx, Supervisor config
ml-service/           Flask recommendation service
database/             Laravel migrations/factories/seeders
```

Dokumentasi operasional:

- [DOCKER.md](DOCKER.md) untuk setup Docker, rootless deployment, dan command operasional.
- [PAYMENT_FLOW.md](PAYMENT_FLOW.md) untuk alur escrow, payment, revisi, dispute, dan pencairan dana.
- [ml-service/README.md](ml-service/README.md) untuk detail training dan endpoint ML service.

## Setup Lokal Tanpa Docker

Pastikan PHP 8.3, Composer, Node.js, MongoDB, dan Python tersedia.

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run dev
php artisan serve
```

Jalankan queue worker di terminal terpisah:

```bash
php artisan queue:work
```

Setup ML service:

```bash
cd ml-service
python -m venv venv
venv\Scripts\activate
pip install -r requirements.txt
python run.py
python app.py
```

Untuk Linux/Mac, aktivasi venv memakai:

```bash
source venv/bin/activate
```

## Setup Dengan Docker

Development:

```bash
docker compose up --build
```

Production/rootless:

```bash
docker compose -f docker-compose.prod.yml up --build -d
```

Baca detail lengkap di [DOCKER.md](DOCKER.md).

## Environment

Minimal variable yang perlu dicek:

```env
APP_KEY=
APP_URL=
DB_CONNECTION=mongodb
DB_HOST=
DB_DATABASE=konekin
ML_SERVICE_URL=http://127.0.0.1:5000
CLOUDINARY_URL=
MIDTRANS_SERVER_KEY=
MIDTRANS_CLIENT_KEY=
JWT_SECRET=
```

Jangan commit credential asli. `.env.example` hanya untuk template.

## Command Harian

```bash
php artisan route:list
php artisan optimize:clear
php artisan test
npm run build
```

Docker:

```bash
docker compose ps
docker compose logs -f app
docker compose exec app php artisan migrate --force
docker compose exec app php artisan queue:failed
```

## Catatan Maintenance

- Jangan commit `vendor`, `node_modules`, `ml-service/venv`, cache, log, atau file credential.
- Proposal dan dokumen user dibuka melalui route Laravel agar aksesnya tetap terproteksi.
- Payment/escrow memiliki banyak status. Jika menambah flow baru, update [PAYMENT_FLOW.md](PAYMENT_FLOW.md).
- Setelah mengubah route/config/view di production, jalankan `php artisan optimize:clear` lalu cache ulang jika diperlukan.
