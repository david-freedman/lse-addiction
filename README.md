# LSE Addiction

Laravel 12 cайт для управління курсами та клієнтами.

## Початкове налаштування

```bash
# Запустити Docker контейнери
docker compose up -d

# Встановити залежності та налаштувати Laravel
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
docker compose exec app php artisan storage:link

# Встановити frontend залежності
npm install
```

## Розробка

### Backend

Усі команди для роботи з Laravel доступні в `Makefile`. `make` для перегляду всіх команд.

### Frontend

Для автоматичного оновлення змін в JS та CSS файлах:

```bash
npm run dev
```

Для production збірки:

```bash
npm run build
```

## URL адреси

- Сайт: http://lse-addiction.loc
- Mailpit (тестування email): http://lse-addiction.loc:8025
- PostgreSQL: localhost:5432
- Redis: localhost:6379
