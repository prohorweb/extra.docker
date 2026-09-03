# WordPress deployment artifact

Deployable WordPress content — **no core files**. Core comes from Docker image `wordpress:7.1-php8.2-fpm-alpine` (volume `wp_core`).

| Path | Deploy |
|------|--------|
| `wp-content/themes/extrasport/` | bind-mount |
| `wp-content/uploads/` | bind-mount (tracked in git) |
| `wp-config.php` | bind-mount read-only |
| `scripts/wordpress-import-db.sh` | run on host at deploy |

## Docker Compose

| File | Purpose |
|------|---------|
| [`docker-compose.yml`](docker-compose.yml) | WordPress + nginx + MariaDB + phpMyAdmin |
| [`docker-compose.dev.yml`](docker-compose.dev.yml) | + legacy Yii2/Laravel stack |

```bash
# Production-like (from repo root)
docker compose -f deploy/docker-compose.yml up -d

# Dev with legacy sites
docker compose -f deploy/docker-compose.yml -f deploy/docker-compose.dev.yml up -d --build
```

## Quick start

```bash
docker compose up -d
./deploy/scripts/wordpress-import-db.sh path/to/dump.sql
cd deploy/wp-content/themes/extrasport && npm install && npm run build
```

Docs: [WORDPRESS_SETUP.md](WORDPRESS_SETUP.md), [docs/WORDPRESS_UPDATE.md](docs/WORDPRESS_UPDATE.md)

Env: [.env.example](.env.example)

Legacy Yii/Laravel: separate MariaDB (`legacy_mariadb`, port 3307) with `extra` + `extra_new` from `legacy/db/`.
