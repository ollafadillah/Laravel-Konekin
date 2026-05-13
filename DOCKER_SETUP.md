# Docker Setup untuk Konekin Laravel Project

Dokumentasi lengkap setup Docker untuk deployment rootless.

## 📋 Daftar File Docker

```
Dockerfile                          # Multi-stage Docker image
docker-compose.yml                  # Orchestration services
.dockerignore                       # Files to exclude from Docker build
.env.docker                         # Environment configuration untuk Docker

docker/
├── php/
│   ├── php.ini                     # PHP configuration
│   └── php-fpm.conf                # PHP-FPM configuration
├── nginx/
│   ├── nginx.conf                  # Nginx main configuration
│   └── default.conf                # Nginx site configuration
├── supervisor/
│   └── supervisord.conf            # Supervisor configuration
└── entrypoint.sh                   # Container initialization script
```

## 🔐 Keamanan Rootless

### User Non-Root
- Container berjalan dengan user `app` (UID: 1000)
- Tidak ada akses root di dalam container
- Proper file ownership dan permissions

### Security Options di docker-compose.yml
```yaml
security_opt:
  - no-new-privileges:true    # Proses tidak bisa mendapat privilege lebih tinggi
cap_drop:
  - ALL                        # Drop semua capabilities
cap_add:
  - CHOWN                      # Hanya tambah yang diperlukan
  - DAC_OVERRIDE
  - SETGID
  - SETUID
```

## 🚀 Cara Penggunaan

### 1. Build Docker Image
```bash
docker build -t konekin:latest .
```

### 2. Build dan Run dengan Docker Compose
```bash
docker-compose up -d
```

### 3. View Logs
```bash
docker-compose logs -f app
docker-compose logs -f mongodb
```

### 4. Stop Services
```bash
docker-compose down
```

### 5. Stop dan Hapus Data
```bash
docker-compose down -v
```

## 🔧 Environment Configuration

Sebelum menjalankan, copy dan update `.env.docker`:

```bash
cp .env.docker .env.docker.local
```

Edit file dengan konfigurasi yang sesuai:
- `APP_KEY` - Generate dengan `php artisan key:generate` (local development)
- `MIDTRANS_SERVER_KEY` - Dari Midtrans Dashboard
- `MIDTRANS_CLIENT_KEY` - Dari Midtrans Dashboard
- `CLOUDINARY_URL` - Dari Cloudinary Console
- `JWT_SECRET` - Generate JWT secret

## 📝 Konfigurasi Detail

### PHP Configuration (`docker/php/php.ini`)
- Memory limit: 512MB
- Upload max: 100MB
- OPCache enabled untuk performance
- Timezone: Asia/Jakarta

### PHP-FPM Configuration (`docker/php/php-fpm.conf`)
- User: `app` (non-root)
- Dynamic process manager
- Max 20 children processes
- Process idle timeout: 10 detik
- Disable dangerous functions untuk security

### Nginx Configuration (`docker/nginx/default.conf`)
- Listen pada port 8000 (non-root compatible)
- Security headers enabled
- Gzip compression
- Static files caching
- Health check endpoint `/health`

### Supervisor Configuration (`docker/supervisor/supervisord.conf`)
- PHP-FPM management
- Nginx management
- Laravel Queue Workers (2 processes)
- User: `app` (non-root)

## 🔄 Services

### 1. **app** (Konekin Laravel Application)
- Image: Custom built dari Dockerfile
- Ports: `8000:8000`
- Includes: PHP-FPM, Nginx, Supervisor

### 2. **mongodb** (Database)
- Image: `mongo:7.0-alpine`
- Ports: `27017:27017`
- Volume: `mongodb_data` untuk persistence

## 📊 Health Checks

### App Health Check
Endpoint: `http://localhost:8000/health`

Dijalankan setiap 30 detik dengan timeout 10 detik.

## 🐛 Troubleshooting

### Container tidak bisa write ke storage
```bash
# Check permissions
docker-compose exec app ls -la storage

# Fix permissions (dari host)
sudo chown -R 1000:1000 ./storage ./bootstrap/cache
```

### MongoDB connection error
```bash
# Check MongoDB logs
docker-compose logs mongodb

# Verify connection
docker-compose exec app php artisan tinker
>>> DB::connection()->getPdo()
```

### Port sudah terpakai
```bash
# Change port di docker-compose.yml
ports:
  - "8001:8000"  # Host:Container
```

### Clear dan rebuild cache
```bash
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
```

## 🔗 Akses Aplikasi

- **Laravel App**: http://localhost:8000
- **Health Check**: http://localhost:8000/health
- **MongoDB**: localhost:27017

## 📦 Dependencies

- PHP 8.3
- Node.js 20
- MongoDB 7.0
- Nginx (inside container)
- Supervisor (inside container)

## 🛠️ Multi-stage Build Benefits

1. **Builder Stage**
   - Install semua development dependencies
   - Build assets dengan npm/Vite
   - Generate cache files
   - Composer optimization

2. **Production Stage**
   - Hanya runtime dependencies
   - Copy hasil build dari builder
   - Ukuran image lebih kecil
   - Security lebih baik

## 📈 Performance Optimization

- OPCache untuk PHP bytecode caching
- Gzip compression untuk assets
- Static files caching (1 tahun)
- Nginx buffer optimization
- PHP-FPM process management yang efisien

## 🔐 Security Best Practices

✅ Rootless container (user `app`)
✅ No new privileges
✅ Drop semua capabilities yang tidak perlu
✅ Security headers di Nginx
✅ Disable dangerous PHP functions
✅ Health check endpoint
✅ Proper file permissions
✅ Minimal image (multi-stage build)

## 📝 Notes

- Container berjalan rootless dengan user `app` UID 1000
- Semua services managed oleh Supervisor
- Queue workers auto-restart jika crash
- Logs diarahkan ke stdout/stderr untuk Docker logging
- Volume binds untuk storage persistence

## 🎯 Production Deployment

Untuk production deployment:

1. Build image:
```bash
docker build -t konekin:latest .
docker tag konekin:latest your-registry/konekin:latest
docker push your-registry/konekin:latest
```

2. Deploy dengan orchestration tool (Kubernetes, Docker Swarm, etc.)

3. Update environment variables di deployment platform

4. Setup monitoring dan logging

5. Configure backup untuk MongoDB data

---

**Last Updated**: 2026
**Laravel Version**: 13.x
**PHP Version**: 8.3
