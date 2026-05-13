# Docker Setup

Stack Docker ini menjalankan:

- Laravel app di `http://localhost:8000`
- Flask ML service di `http://localhost:5000`
- MongoDB di `localhost:27017`
- Queue worker Laravel lewat Supervisor di container app

## Quick Start

```bash
docker compose up --build
```

Untuk produksi lokal/rootless:

```bash
docker compose -f docker-compose.prod.yml up --build -d
```

Production reverse proxy tersedia di `http://localhost:8080`.

## Environment

Update `.env.docker` sebelum deploy:

- `APP_KEY`
- `APP_URL`
- `JWT_SECRET`
- `MIDTRANS_SERVER_KEY`
- `MIDTRANS_CLIENT_KEY`
- `CLOUDINARY_URL`

Default ML service internal:

```env
ML_SERVICE_URL=http://ml-service:5000
```

## Startup Toggles

- `AUTO_MIGRATE=true|false`: jalankan migrasi saat app start.
- `AUTO_OPTIMIZE=true|false`: rebuild config/route/view cache setelah env runtime masuk.
- `AUTO_STORAGE_LINK=true|false`: buat symlink storage publik.
- `QUEUE_WORKERS=2`: jumlah worker queue di Supervisor.

Di `docker-compose.yml`, `AUTO_MIGRATE` aktif untuk memudahkan development.
Di `docker-compose.prod.yml`, `AUTO_MIGRATE` default `false`; jalankan migrasi manual saat release:

```bash
docker compose -f docker-compose.prod.yml exec app php artisan migrate --force
```

## Rootless Deployment Notes

Compose production sudah disiapkan untuk rootless:

- Semua published port di atas 1024 (`8080`), jadi tidak butuh privileged bind.
- App dan ML service berjalan sebagai user non-root UID/GID `1000`.
- Nginx proxy memakai image unprivileged.
- Capabilities container di-drop dan `no-new-privileges` aktif.
- Data production memakai named volumes agar lebih aman untuk Docker rootless dibanding bind mount host.

Kalau UID/GID user server bukan `1000`, jalankan:

```bash
UID=$(id -u) GID=$(id -g) docker compose -f docker-compose.prod.yml up --build -d
```

Untuk expose ke domain dengan TLS, arahkan reverse proxy host atau load balancer ke `localhost:8080`.
