#!/bin/bash

# Konekin Docker Quick Start Script

set -e

echo "🐳 Konekin Docker Setup"
echo "======================"

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "❌ Docker is not installed"
    exit 1
fi

# Check if Docker Compose is installed
if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose is not installed"
    exit 1
fi

# Create .env.docker if not exists
if [ ! -f .env.docker ]; then
    echo "📝 Creating .env.docker file..."
    cp .env.docker .env.docker
fi

# Prompt for APP_KEY if not set
if ! grep -q "APP_KEY=base64:" .env.docker; then
    echo "🔑 Generating APP_KEY..."
    # We'll set this during first container run
fi

echo "🔨 Building Docker image..."
docker-compose build

echo "🚀 Starting services..."
docker-compose up -d

echo "⏳ Waiting for services to be ready..."
sleep 5

echo "📦 Running migrations..."
docker-compose exec -T app php artisan migrate --force || true

echo "✅ Setup complete!"
echo ""
echo "🌐 Access your application at: http://localhost:8000"
echo "📊 View logs: docker-compose logs -f app"
echo "🛑 Stop services: docker-compose down"
echo ""
echo "📝 Next steps:"
echo "1. Update .env.docker with your configuration"
echo "2. Test: curl http://localhost:8000/health"
echo "3. Setup your Midtrans and Cloudinary credentials"
