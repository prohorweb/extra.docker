# Deploy & Dev Workflow — extra.docker

Полный процесс локальной разработки и production-like деплоя WordPress Multisite (extrasport + devision).

---

## 1. Структура репозитория

```
extra.docker/
├── deploy/                    # В git — артефакты WordPress deploy
│   ├── docker-compose.yml     # WP + nginx + MariaDB + phpMyAdmin
│   ├── docker-compose.dev.yml # overlay: legacy Yii2/Laravel
│   ├── wp-config.php
│   ├── wp-content/
│   │   ├── themes/extrasport/
│   │   └── uploads/           # bind-mount, в git
│   ├── scripts/
│   │   └── wordpress-import-db.sh
│   └── legacy-db-init/
│       └── import-legacy.sh   # импорт legacy/db/*.sql (dev)
├── legacy/                    # НЕ в git — локальный архив Yii2/Laravel
├── nginx.conf                 # WP multisite (extrasport.local, devision.local)
├── nginx.legacy-proxy.conf    # dev: extra.local → legacy_nginx
├── docker-compose.yml         # include deploy/docker-compose.yml
└── .env                       # gitignored — WORDPRESS_* для WP
```

---

## 2. Docker Compose

### Production-like (только WordPress)

```bash
docker compose up -d
# или
docker compose -f deploy/docker-compose.yml up -d
```

### Dev (WordPress + legacy сайты)

```bash
docker compose -f deploy/docker-compose.yml -f deploy/docker-compose.dev.yml up -d --build
```

| Файл | Назначение |
|------|------------|
| `deploy/docker-compose.yml` | WordPress 7.1 FPM, nginx, MariaDB, phpMyAdmin |
| `deploy/docker-compose.dev.yml` | + legacy_php, legacy_laravel, legacy_nginx, legacy_node, **legacy_mariadb** |

---

## 3. Базы данных (разделены)

| Стек | Контейнер | Volume | БД | Доступ |
|------|-----------|--------|-----|--------|
| **WordPress deploy** | `extra_mariadb` | `db_data` (compose-managed) | `extra` | phpMyAdmin :8081 **dev only** |
| **Legacy dev** | `legacy_mariadb` | `deploy_legacy_db_data` | `extra` (Yii2), `extra_new` (Laravel) | host `:3307` |

WordPress **не** использует `extra_new`. Legacy **не** шарит MariaDB с WP.

Импорт legacy при **первом** старте `legacy_mariadb`: `deploy/legacy-db-init/import-legacy.sh` читает дампы из `legacy/db/`.

---

## 4. Первый запуск (чеклист)

### 4.1 `/etc/hosts`

```
127.0.0.1 extrasport.local devision.local
127.0.0.1 extra.local piter.extra.local matros.extra.local
127.0.0.1 extra.new piter.extra.new matros.extra.new de-vision.new
```

### 4.2 Окружение

```bash
cp deploy/.env.example .env
# WORDPRESS_DB_NAME=extra
# WORDPRESS_DB_USER=extra
# WORDPRESS_DB_PASSWORD=extra123
# WORDPRESS_LANG через compose: ru_RU
```

### 4.3 Поднять стек

```bash
# только WP
docker compose -f deploy/docker-compose.yml up -d

# WP + legacy (нужна папка legacy/ на диске)
docker compose -f deploy/docker-compose.yml -f deploy/docker-compose.dev.yml up -d --build
```

### 4.4 Импорт БД WordPress (если volume пустой)

```bash
./deploy/scripts/wordpress-import-db.sh path/to/dump.sql
# по умолчанию импорт в БД extra
```

### 4.5 Сборка темы

```bash
cd deploy/wp-content/themes/extrasport
npm install && npm run build
```

### 4.6 WordPress admin — после обновления core

1. **Network Admin → Upgrade Network** — миграция схемы БД Multisite (обязательно после WP 7.1).
2. **Re-install 7.1–ru_RU** (Updates) — опционально, один раз, для русской админки.  
   Локаль уже задана: `WORDPRESS_LANG=ru_RU` в compose + `WPLANG` в `wp-config.php`.

---

## 5. URL и сервисы

| Что | URL |
|-----|-----|
| WordPress extrasport | https://extrasport.local |
| WordPress devision | https://devision.local |
| Yii2 legacy | http://extra.local (порт 80, proxy через `nginx.legacy-proxy.conf`) |
| Laravel legacy | https://extra.new (proxy → legacy_nginx:443) |
| phpMyAdmin (WP DB) | http://localhost:8081 |
| Legacy MariaDB (CLI) | `localhost:3307` |

---

## 6. Модель deploy WordPress

| Компонент | Источник |
|-----------|----------|
| Core 7.1 | образ `wordpress:7.1-php8.2-fpm-alpine` → volume `extradocker_wp_core` |
| Тема | bind-mount `deploy/wp-content/themes/extrasport` |
| Uploads | bind-mount `deploy/wp-content/uploads` |
| Config | `deploy/wp-config.php` (ro) |
| БД | MariaDB `extra`, импорт SQL отдельно |

Legacy **не монтируется** в WP-контейнеры. Yii/import-скрипты темы перенесены в `legacy/wordpress-import/` (не загружаются в prod).

---

## 7. Обновление WordPress

1. Бэкап: `docker exec extra_mariadb mysqldump -uextra -pextra123 extra > backup.sql`
2. Обновить тег образа в `deploy/docker-compose.yml`
3. `docker compose pull wordpress && docker compose up -d wordpress`
4. **Upgrade Network** в админке
5. `npm run build` в теме
6. Smoke-test оба домена

Подробнее: `deploy/docs/WORDPRESS_UPDATE.md`

---

## 8. Git

- `legacy/` — в `.gitignore`, локально только
- `deploy/wp-content/uploads/` — **в git**
- `.env` — gitignored
- Import/migration PHP темы — архив в `legacy/wordpress-import/`

---

## 9. Типичные проблемы

| Симптом | Причина | Решение |
|---------|---------|---------|
| Error establishing database connection | неверный volume / .env | volume `extradocker_wp_db_data`, `.env` → `extra` |
| extra.local → extrasport.local | запрос шёл в WP nginx | dev compose + `nginx.legacy-proxy.conf` |
| extra.local 500 / settings table | Yii ходил в `db` (WP) | `legacy/common/config/main-local.php` → `legacy-db` |
| Legacy DB пустая | дампы без `USE extra` | `deploy/legacy-db-init/import-legacy.sh`, пересоздать `deploy_legacy_db_data` |
