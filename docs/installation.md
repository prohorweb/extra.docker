# Установка и настройка

## Быстрый старт

### 1. Домены в `/etc/hosts`

**macOS / Linux:**
```bash
sudo nano /etc/hosts
```

**Windows (Блокнот от администратора):**
Открыть `C:\Windows\System32\drivers\etc\hosts`

Добавить:
```text
127.0.0.1 extra.new
127.0.0.1 piter.extra.new
127.0.0.1 matros.extra.new
127.0.0.1 de-vision.new
```

### 2. HTTPS-сертификаты (один раз на машину)

Домены `.new` требуют обязательного HTTPS. См. [HTTPS_SETUP.md](HTTPS_SETUP.md).

### 3. Запуск контейнеров

```bash
docker compose up -d
```

### 4. Инициализация Laravel

```bash
docker compose exec laravel composer install
docker compose exec laravel cp .env.example .env
docker compose exec laravel php artisan key:generate
docker compose exec laravel php artisan migrate
```

### 5. Инициализация Yii2 (если нужно)

```bash
docker compose exec php php init          # выбрать 0 (Development)
docker compose exec php composer update
docker compose exec php php yii migrate
```

## Полезные команды

### Laravel
```bash
docker compose exec laravel php artisan route:list
docker compose exec laravel php artisan make:controller NameController
docker compose exec laravel php artisan make:model ModelName -m
docker compose exec laravel php artisan migrate
docker compose exec laravel php artisan migrate:fresh --seed
docker compose exec laravel php artisan tinker
```

### Yii2
```bash
docker compose exec php php yii migrate
docker compose exec php composer require vendor/package
```

### Общие
```bash
docker compose ps
docker compose logs -f nginx
docker compose logs -f laravel
docker compose restart nginx
docker compose down
docker compose down -v   # ⚠️ удалит БД!
```

## Доступы

- **phpMyAdmin:** http://localhost:8081
  - Логин: `root` / Пароль: `root123`
  - Логин: `extra` / Пароль: `extra123`
- **БД (внешний доступ):** `127.0.0.1:3306`