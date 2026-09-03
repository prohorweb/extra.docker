# Обновление WordPress (Docker + Multisite)

Инструкция для проекта **extra.docker**: deployment model на официальном Docker-образе WordPress 7.1+.

---

## Deployment model (актуально)

| Компонент | Источник |
|-----------|----------|
| WordPress core | Образ `wordpress:7.1-php8.2-fpm-alpine` → volume `wp_core` |
| Тема `extrasport` | bind-mount `deploy/wp-content/themes/extrasport` |
| Uploads | bind-mount `deploy/wp-content/uploads` |
| `wp-config.php` | `deploy/wp-config.php` (read-only mount) |
| База данных | Импорт SQL при деплое — **не** из bind-mount `./wordpress/` |

**Ядро WordPress больше не хранится в deploy.** Архивный core 6.4 — в [`../../legacy/wordpress/`](../../legacy/wordpress/) (не монтируется).

```yaml
# docker-compose.yml (фрагмент)
wordpress:
  image: wordpress:7.1-php8.2-fpm-alpine
  volumes:
    - wp_core:/var/www/html
    - ./deploy/wp-content/themes/extrasport:/var/www/html/wp-content/themes/extrasport
    - ./deploy/wp-content/uploads:/var/www/html/wp-content/uploads
    - ./deploy/wp-config.php:/var/www/html/wp-config.php:ro
```

Обновление core = смена тега образа + `docker compose pull wordpress && docker compose up -d wordpress`. Файлы core в volume обновляются entrypoint-ом образа при пересоздании контейнера (при необходимости — удалить volume `wp_core` на чистом стенде).

---

## Импорт базы данных

```bash
# Бэкап
docker exec extra_mariadb mysqldump -u wordpress -pwordpress123 wordpress > backup-wordpress-$(date +%Y%m%d).sql

# Восстановление
./deploy/scripts/wordpress-import-db.sh backup-wordpress-20260903.sql

# Другая БД (legacy extra_new):
./deploy/scripts/wordpress-import-db.sh dump.sql --database extra_new
```

Переменные окружения: `.env.wordpress.example`

---

## Обновление образа (7.1 → патч)

1. Бэкап БД (см. выше)
2. Обновить тег в `docker-compose.yml`
3. `docker compose pull wordpress && docker compose up -d wordpress`
4. `docker compose exec wordpress wp core update-db --allow-root` (если установлен wp-cli в контейнере) или через админку
5. Пересобрать тему: `cd deploy/wp-content/themes/extrasport && npm run build`
6. Smoke-test multisite: оба домена, формы, админка

---

## Multisite

Domain-based multisite настраивается в `deploy/wp-config.php`:

- `DOMAIN_CURRENT_SITE` — env `WORDPRESS_DOMAIN_CURRENT_SITE` (default: `extrasport.local`)
- `COOKIEDOMAIN` — env `WORDPRESS_COOKIE_DOMAIN` (default: `.local`)

На production обновить env перед деплоем.

---

## Связанные файлы

- [docker-compose.yml](../../docker-compose.yml)
- [wp-config.php](../wp-config.php)
- [scripts/wordpress-import-db.sh](../scripts/wordpress-import-db.sh)
- [README.md](../README.md)
