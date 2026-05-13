#!/bin/sh
set -eu

echo "Konekin Docker Setup"
echo "===================="

if ! command -v docker >/dev/null 2>&1; then
    echo "Docker is not installed or not available in PATH."
    exit 1
fi

if ! docker compose version >/dev/null 2>&1; then
    echo "Docker Compose v2 is not available."
    exit 1
fi

if [ ! -f .env.docker ]; then
    echo ".env.docker is missing. Create it from .env.example before running this script."
    exit 1
fi

echo "Building images..."
docker compose build

echo "Starting services..."
docker compose up -d

echo "Current containers:"
docker compose ps

echo ""
echo "Done."
echo "Laravel: http://localhost:8000"
echo "ML service: http://localhost:5000/health"
echo "Logs: docker compose logs -f app"
