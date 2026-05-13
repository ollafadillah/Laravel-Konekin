# 🐳 Docker Quick Reference - Konekin

## Startup Commands

```bash
# Development
make up                    # Start all services
make logs                  # View application logs
docker-compose ps         # Show running containers

# Production
make prod-up              # Start with production config
make prod-down            # Stop production services
```

## Common Operations

```bash
# Application shell
make shell                # Login to app container

# Database operations
make migrate              # Run migrations
make seed                 # Run seeders

# Cache operations
make cache-clear          # Clear all caches
make cache-build          # Rebuild caches

# Laravel commands
docker-compose exec app php artisan <command>

# Queue operations
make queue-work           # Process queue jobs
make queue-failed         # Show failed jobs
make queue-retry          # Retry failed jobs
```

## Debugging

```bash
# View logs
make logs                 # App logs
make logs-mongo           # MongoDB logs
docker-compose logs -f    # All logs

# Check health
make health               # Test health endpoint
curl http://localhost:8000/health

# Database shell
docker-compose exec mongodb mongosh

# MySQL queries
docker-compose exec mysql mysql -u root -p konekin
```

## Container Information

```bash
# List all containers
make ps
docker-compose ps

# Inspect container
docker inspect konekin-app
docker-compose exec app env

# Resource usage
docker stats
```

## Cleanup

```bash
# Stop services
make down

# Stop and remove volumes
docker-compose down -v

# Remove everything
make clean
```

## Rootless Details

- **User**: app (UID: 1000)
- **No Root Access**: ✓ Verified
- **No New Privileges**: ✓ Enabled
- **Minimal Capabilities**: ✓ Configured
- **Volume Permissions**: ✓ Properly set

## Network Access

- **App**: http://localhost:8000
- **Health Check**: http://localhost:8000/health
- **MongoDB**: localhost:27017
- **Production (Nginx)**: http://localhost:80

## Environment Files

- `.env.docker` - Development environment
- `.env.docker.local` - Override (create as needed)
- `docker-compose.yml` - Development compose file
- `docker-compose.prod.yml` - Production compose file

## File Structure

```
Dockerfile                 # Main image definition
docker-compose.yml         # Development setup
docker-compose.prod.yml    # Production setup
.dockerignore              # Docker build ignore
.env.docker                # Docker environment

docker/
├── php/                    # PHP configuration
├── nginx/                  # Nginx (in-container)
├── nginx-proxy/            # Nginx reverse proxy (prod)
└── supervisor/             # Supervisor config
```

---

**For detailed docs**: See `DOCKER_SETUP.md`
