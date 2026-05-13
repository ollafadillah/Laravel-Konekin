.PHONY: help build up down logs shell migrate seed clean

help:
	@echo "Konekin Docker Commands"
	@echo "======================"
	@echo "make build      - Build Docker image"
	@echo "make up         - Start services"
	@echo "make down       - Stop services"
	@echo "make logs       - View logs"
	@echo "make shell      - Shell into app container"
	@echo "make migrate    - Run migrations"
	@echo "make seed       - Run seeders"
	@echo "make clean      - Clean everything"
	@echo "make prod-up    - Start production services"
	@echo "make ps         - Show running containers"

build:
	docker-compose build

up:
	docker-compose up -d

down:
	docker-compose down

logs:
	docker-compose logs -f app

logs-mongo:
	docker-compose logs -f mongodb

shell:
	docker-compose exec app bash

tinker:
	docker-compose exec app php artisan tinker

migrate:
	docker-compose exec app php artisan migrate --force

seed:
	docker-compose exec app php artisan db:seed

cache-clear:
	docker-compose exec app php artisan cache:clear
	docker-compose exec app php artisan config:clear
	docker-compose exec app php artisan route:clear
	docker-compose exec app php artisan view:clear

cache-build:
	docker-compose exec app php artisan config:cache
	docker-compose exec app php artisan route:cache

clean:
	docker-compose down -v
	docker rmi konekin:latest || true

ps:
	docker-compose ps

test:
	docker-compose exec app php artisan test

prod-up:
	docker-compose -f docker-compose.prod.yml up -d

prod-down:
	docker-compose -f docker-compose.prod.yml down

queue-work:
	docker-compose exec app php artisan queue:work --tries=3

queue-failed:
	docker-compose exec app php artisan queue:failed

queue-retry:
	docker-compose exec app php artisan queue:retry all

health:
	curl -s http://localhost:8000/health && echo ""
