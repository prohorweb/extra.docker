# Deployment — Strangler Fig Pattern

---

## Overview

Постепенная миграция маршрутов с Yii2 на Laravel через Nginx.
Yii2 остаётся основным бэкендом, Laravel постепенно перехватывает маршруты.

---

## Strangler Fig Routing Plan

| Route | Target | Phase | Status |
|-------|--------|-------|--------|
| `/` | Laravel | Phase 2 | 🔄 In Progress |
| `/about` | Laravel | Phase 4 | ⏳ Planned |
| `/contacts` | Laravel | Phase 4 | ⏳ Planned |
| `/club` | Laravel | Phase 4 | ⏳ Planned |
| `/legal` | Laravel | Phase 4 | ⏳ Planned |
| `/privacy` | Laravel | Phase 4 | ⏳ Planned |
| `/offer` | Laravel | Phase 4 | ⏳ Planned |
| `/services/*` | Laravel | Phase 5 | ⏳ Planned |
| `/news/*` | Laravel | Phase 5 | ⏳ Planned |
| `/shares/*` | Laravel | Phase 5 | ⏳ Planned |
| `/trainers/*` | Laravel | Phase 5 | ⏳ Planned |
| `/events/*` | Laravel | Phase 5 | ⏳ Planned |
| `/programs/*` | Laravel | Phase 5 | ⏳ Planned |
| `/jobs/*` | Laravel | Phase 5 | ⏳ Planned |
| `/articles/*` | Laravel | Phase 5 | ⏳ Planned |
| `/admin/*` | Laravel | Phase 6b | ⏳ Planned |
| `/*` | Yii2 | — | ✅ Active |

---

## Nginx Configuration

**File**: `/etc/nginx/sites-available/extra.new`

```nginx
upstream yii2_backend {
    server php-fpm:9000;
}

upstream laravel_backend {
    server laravel:9000;
}

server {
    listen 443 ssl http2;
    server_name extra.new;

    ssl_certificate /etc/nginx/ssl/cert.pem;
    ssl_certificate_key /etc/nginx/ssl/key.pem;

    root /var/www;
    index index.php;

    # ──────────────────────────────────
    # Laravel — Vite HMR (Dev only)
    # ──────────────────────────────────
    location /__vite_hmr {
        proxy_pass http://vite:5173;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_read_timeout 86400;
    }

    # ──────────────────────────────────
    # Laravel — Static assets
    # ──────────────────────────────────
    location /build/ {
        alias /var/www/laravel/public/build/;
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    location /storage/ {
        alias /var/www/laravel/storage/app/public/;
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    location /uploads/ {
        alias /var/www/laravel/public/uploads/;
        expires 1y;
        add_header Cache-Control "public";
    }

    location /video/ {
        alias /var/www/laravel/public/video/;
        expires 1w;
        add_header Cache-Control "public";
    }

    # ──────────────────────────────────
    # Strangler Fig — Laravel Routes (Phase by Phase)
    # ──────────────────────────────────
    location = / {
        proxy_pass http://laravel_backend;
    }

    location /admin/ {
        proxy_pass http://laravel_backend;
    }

    location /services/ {
        proxy_pass http://laravel_backend;
    }

    location /news/ {
        proxy_pass http://laravel_backend;
    }

    location /shares/ {
        proxy_pass http://laravel_backend;
    }

    location /trainers/ {
        proxy_pass http://laravel_backend;
    }

    location /events/ {
        proxy_pass http://laravel_backend;
    }

    location /programs/ {
        proxy_pass http://laravel_backend;
    }

    location /jobs/ {
        proxy_pass http://laravel_backend;
    }

    location /articles/ {
        proxy_pass http://laravel_backend;
    }

    # ──────────────────────────────────
    # Default — Yii2 (Legacy)
    # ──────────────────────────────────
    location / {
        try_files $uri $uri/ /index.php?$args;
        location ~ \\.php$ {
            proxy_pass http://yii2_backend;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
            include fastcgi_params;
        }
    }
}
```

---

## Docker Compose

**File**: `docker-compose.yml`

```yaml
version: '3.8'

services:
  nginx:
    image: nginx:alpine
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./nginx.conf:/etc/nginx/conf.d/default.conf
      - ./ssl:/etc/nginx/ssl
    depends_on:
      - php-fpm
      - laravel

  php-fpm:
    image: yiisoftware/yii2-php:8.2-fpm
    volumes:
      - ./frontend:/var/www/frontend
      - ./backend:/var/www/backend
      - ./common:/var/www/common

  laravel:
    build:
      context: ./laravel
      dockerfile: Dockerfile
    volumes:
      - ./laravel:/var/www/laravel

  vite:
    image: node:20-alpine
    working_dir: /var/www/laravel
    volumes:
      - ./laravel:/var/www/laravel
    command: npm run dev

  mysql:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: ${DB_PASSWORD}
      MYSQL_DATABASE: extra_fitness

  redis:
    image: redis:alpine

  mailpit:
    image: axllent/mailpit
    ports:
      - "8025:8025"
```

---

## CI/CD Pipeline (GitHub Actions)

**File**: `.github/workflows/deploy.yml`

```yaml
name: Deploy

on:
  push:
    branches: [main]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
      - run: composer install --no-interaction --prefer-dist
      - run: npm ci
      - run: npm run build
      - run: php artisan test

  deploy:
    needs: test
    runs-on: ubuntu-latest
    steps:
      - name: Deploy via SSH
        uses: appleboy/ssh-action@v1.0.0
        with:
          host: ${{ secrets.DEPLOY_HOST }}
          username: ${{ secrets.DEPLOY_USER }}
          key: ${{ secrets.DEPLOY_KEY }}
          script: |
            cd /var/www/extra
            git pull origin main
            composer install --no-interaction --prefer-dist --no-dev
            npm ci
            npm run build
            php artisan migrate --force
            php artisan queue:restart
            sudo systemctl reload nginx
```

---

## Environment Variables

**File**: `.env` (Laravel)

```bash
APP_NAME="Extra Fitness"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://extra.new

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=extra_fitness
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}

QUEUE_CONNECTION=redis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=${MAIL_HOST}
MAIL_PORT=587
MAIL_USERNAME=${MAIL_USERNAME}
MAIL_PASSWORD=${MAIL_PASSWORD}
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@extra.new
MAIL_FROM_NAME="Extra Fitness"

FILESYSTEM_DISK=public
VAPOR_TIMEOUT=300

SENTRY_LARAVEL_DSN=${SENTRY_DSN}
```

---

## Health Checks

**File**: `routes/web.php`

```php
Route::get('/up', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
    ]);
});

Route::get('/health', function () {
    try {
        DB::connection()->getPdo();
        return response()->json([
            'status' => 'healthy',
            'database' => 'connected',
            'queue' => Queue::size() < 100 ? 'ok' : 'high_load',
            'cache' => Cache::has('health_check') || Cache::set('health_check', true, 10),
            'timestamp' => now(),
        ]);
    } catch (\\Exception $e) {
        return response()->json(['status' => 'unhealthy', 'error' => $e->getMessage()], 500);
    }
});
```

---

## Rollback Procedure

### 1. Revert Route to Yii2
```bash
# Временно переключить маршрут обратно на Yii2
# Раскомментировать в nginx.conf location, изменить proxy_pass на yii2_backend
sudo nginx -t && sudo systemctl reload nginx
```

### 2. Database Rollback
```bash
php artisan migrate:rollback --step=1
```

### 3. Full Rollback
```bash
git revert HEAD
sudo systemctl reload nginx
php artisan migrate:rollback --batch=1
```

---

## Monitoring

| Tool | Purpose | URL |
|------|---------|-----|
| Laravel Telescope | Dev debugging | `/telescope` |
| Sentry | Error tracking | Sentry dashboard |
| Mailpit | Email testing (dev) | `http://localhost:8025` |
| Grafana | Metrics (prod) | Grafana dashboard |

---

## Navigation

- [← Roadmap](./roadmap.md)
- [Phases →](./phases.md)
- [Admin Panel →](./admin-panel.md)
- [Troubleshooting →](./troubleshooting.md)