.PHONY: help up down restart rebuild logs shell env-reload composer artisan db-restart db-logs db-shell

help:
	@echo "LSE - Makefile commands"
	@echo ""
	@echo "Docker управління (app):"
	@echo "  make up              - Запуск app і db контейнерів"
	@echo "  make down            - Зупинка всіх контейнерів"
	@echo "  make restart         - Перезапуск app контейнера"
	@echo "  make rebuild         - Перебудова app контейнера (build + restart)"
	@echo "  make logs            - Перегляд логів app"
	@echo "  make shell           - Bash в app контейнері"
	@echo "  make env-reload      - Перезавантажити .env (restart + clear cache)"
	@echo ""
	@echo "Artisan команди:"
	@echo "  make artisan migrate              - Запуск міграцій"
	@echo "  make artisan migrate:fresh --seed - Fresh міграція + seed"
	@echo "  make artisan tinker               - Laravel tinker"
	@echo "  make artisan test                 - Запуск тестів"
	@echo "  make artisan queue:listen         - Queue worker"
	@echo "  make artisan pail                 - Log monitoring"
	@echo "  make artisan cache:clear          - Очистка кешу"
	@echo "  make artisan <cmd>                - Будь-яка artisan команда"
	@echo ""
	@echo "Composer:"
	@echo "  make composer install      - Встановлення залежностей"
	@echo "  make composer require pkg  - Додати пакет"
	@echo "  make composer update       - Оновлення залежностей"
	@echo "  make composer <cmd>        - Будь-яка composer команда"
	@echo ""
	@echo "Інше:"
	@echo "  make artisan pint          - Code style fixer (vendor/bin/pint)"
	@echo ""
	@echo "Database (db):"
	@echo "  make db-restart      - Перезапуск db контейнера"
	@echo "  make db-logs         - Перегляд логів db"
	@echo "  make db-shell        - PostgreSQL консоль (psql)"

up:
	docker compose up -d app db nginx

down:
	docker compose down

restart:
	docker compose restart app

rebuild:
	docker compose build --no-cache app
	docker compose up -d app
	docker compose restart app
	@echo "✓ App перебудовано та перезапущено"

logs:
	docker compose logs -f app

shell:
	docker compose exec app bash

env-reload:
	docker compose exec app php artisan config:clear
	docker compose exec app php artisan cache:clear
	docker compose restart app
	@echo "✓ Оточення перезавантажено"

composer:
	docker compose exec app composer $(filter-out $@,$(MAKECMDGOALS))

artisan:
	docker compose exec app php artisan $(filter-out $@,$(MAKECMDGOALS))

db-restart:
	docker compose restart db

db-logs:
	docker compose logs -f db

db-shell:
	docker compose exec db psql -U ${DB_USERNAME} -d ${DB_DATABASE}

%:
	@:
