# Docker Guide

Dokumen ini adalah panduan utama untuk menjalankan Konekin dengan Docker. File Docker lama sudah diringkas ke sini agar maintenance lebih mudah.

## Service

```text
app                 Laravel app, PHP-FPM/runtime, queue worker via Supervisor
ml-service          Flask recommendation service
mongodb             MongoDB database
nginx-reverse-proxy Production reverse proxy, hanya di docker-compose.prod.yml
```

Endpoint development:

- Laravel: `http://localhost:8000`
- ML health: `http://localhost:5000/health`
- MongoDB: `localhost:27017`

Endpoint production/rootless:

- Reverse proxy: `http://localhost:8080`

## File Penting

```text
Dockerfile
docker-compose.yml
docker-compose.prod.yml
.dockerignore
.env.docker
docker/
  entrypoint.sh
  laravel-entrypoint.sh
  php.ini
  php/php.ini
  php/php-fpm.conf
  nginx/nginx.conf
  nginx/default.conf
  nginx-proxy/nginx.conf
  nginx-proxy/conf.d/default.conf
  supervisor/supervisord.conf
ml-service/
  Dockerfile
  .dockerignore
```

## Development

```bash
docker compose up --build
```

Compose membaca environment dari `.env.docker`. Untuk development lokal, isi nilai yang diperlukan di file itu atau override lewat environment shell.

Command umum:

```bash
docker compose ps
docker compose logs -f app
docker compose logs -f ml-service
docker compose exec app php artisan route:list
docker compose exec app php artisan optimize:clear
docker compose exec mongodb mongosh
```

`docker-compose.yml` sengaja mengaktifkan `AUTO_MIGRATE=true` agar development lebih cepat.

## Production / Rootless

```bash
docker compose -f docker-compose.prod.yml up --build -d
```

Jika UID/GID user server bukan `1000`, jalankan:

```bash
UID=$(id -u) GID=$(id -g) docker compose -f docker-compose.prod.yml up --build -d
```

Migrasi production dijalankan manual:

```bash
docker compose -f docker-compose.prod.yml exec app php artisan migrate --force
```

## Rootless Notes

Production compose disiapkan agar cocok untuk Docker rootless:

- Published port memakai `8080`, bukan port privileged di bawah `1024`.
- App dan ML service dibuild dengan UID/GID non-root.
- Nginx memakai `nginxinc/nginx-unprivileged`.
- `no-new-privileges` aktif.
- Container capabilities di-drop.
- Data production memakai named volumes agar permission lebih stabil.

Untuk domain dan TLS, arahkan reverse proxy host atau load balancer ke `localhost:8080`.

## Environment

Update `.env.docker` sebelum deploy:

```env
APP_KEY=
APP_URL=
APP_DEBUG=false
DB_DATABASE=konekin
ML_SERVICE_URL=http://ml-service:5000
CLOUDINARY_URL=
MIDTRANS_SERVER_KEY=
MIDTRANS_CLIENT_KEY=
JWT_SECRET=
```

Jangan isi credential production di file yang ikut commit.

## Startup Toggles

```env
WAIT_FOR_DB=true
AUTO_STORAGE_LINK=true
AUTO_MIGRATE=false
AUTO_OPTIMIZE=true
QUEUE_WORKERS=2
```

- `WAIT_FOR_DB`: app menunggu MongoDB siap.
- `AUTO_STORAGE_LINK`: membuat symlink storage publik.
- `AUTO_MIGRATE`: menjalankan migration saat container start.
- `AUTO_OPTIMIZE`: rebuild cache config/route/view setelah env runtime masuk.
- `QUEUE_WORKERS`: jumlah worker queue Supervisor.

## Troubleshooting

```bash
docker compose logs -f app
docker compose logs -f ml-service
docker compose logs -f mongodb
docker compose exec app php artisan optimize:clear
docker compose exec app php artisan queue:failed
docker compose down
docker compose down -v
```

Gunakan `down -v` hanya jika memang ingin menghapus volume database/cache.
