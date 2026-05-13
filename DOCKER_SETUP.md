# Docker Setup untuk Konekin

Dokumen utama ada di [DOCKER.md](DOCKER.md). File ini merangkum struktur dan alur deploy.

## Struktur

```text
Dockerfile
docker-compose.yml
docker-compose.prod.yml
.dockerignore
.env.docker
docker/
  entrypoint.sh
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
docker compose up --build -d
docker compose logs -f app
```

Endpoint:

- Laravel: `http://localhost:8000`
- ML service: `http://localhost:5000/health`
- MongoDB: `localhost:27017`

## Production Rootless

```bash
docker compose -f docker-compose.prod.yml up --build -d
```

Endpoint production reverse proxy:

- `http://localhost:8080`

Rootless notes:

- Tidak ada published port di bawah 1024.
- App dan ML service berjalan sebagai UID/GID `1000` secara default.
- Nginx proxy memakai image unprivileged.
- Production memakai named volumes untuk mengurangi masalah permission bind mount.

Jika UID/GID server berbeda:

```bash
UID=$(id -u) GID=$(id -g) docker compose -f docker-compose.prod.yml up --build -d
```

## Migrasi Production

`AUTO_MIGRATE` default `false` di production. Jalankan manual saat release:

```bash
docker compose -f docker-compose.prod.yml exec app php artisan migrate --force
```

## Troubleshooting

```bash
docker compose ps
docker compose logs -f app
docker compose logs -f ml-service
docker compose logs -f mongodb
docker compose exec app php artisan optimize:clear
```
