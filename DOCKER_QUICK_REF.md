# Docker Quick Reference

## Development

```bash
docker compose up --build -d
docker compose logs -f app
docker compose logs -f ml-service
docker compose ps
```

Laravel: `http://localhost:8000`
ML health: `http://localhost:5000/health`
MongoDB: `localhost:27017`

## Production / Rootless

```bash
docker compose -f docker-compose.prod.yml up --build -d
docker compose -f docker-compose.prod.yml logs -f
docker compose -f docker-compose.prod.yml exec app php artisan migrate --force
```

Reverse proxy: `http://localhost:8080`

## Common Commands

```bash
docker compose exec app php artisan tinker
docker compose exec app php artisan config:clear
docker compose exec app php artisan route:cache
docker compose exec app php artisan queue:failed
docker compose exec mongodb mongosh
```

## Cleanup

```bash
docker compose down
docker compose down -v
```
