# extra.docker

Infrastructure mono-repo for **ExtraSport** WordPress Multisite deployment.

| Directory | Purpose |
|-----------|---------|
| [`deploy/`](deploy/) | WordPress theme, config, Docker compose |
| [`legacy/`](legacy/) | Yii2/Laravel archive (local only, **not in git**) |

## WordPress (production)

```bash
docker compose up -d
# or: docker compose -f deploy/docker-compose.yml up -d

# /etc/hosts: 127.0.0.1 extrasport.local devision.local

./deploy/scripts/wordpress-import-db.sh path/to/wordpress-dump.sql

cd deploy/wp-content/themes/extrasport && npm install && npm run build
```

- **Compose:** [`deploy/docker-compose.yml`](deploy/docker-compose.yml)
- **Docs:** [deploy/WORDPRESS_SETUP.md](deploy/WORDPRESS_SETUP.md)

## Dev (WordPress + legacy Yii2/Laravel)

```bash
docker compose -f deploy/docker-compose.yml -f deploy/docker-compose.dev.yml up -d --build
```

| Site | URL |
|------|-----|
| WordPress extrasport | https://extrasport.local |
| WordPress devision | https://devision.local |
| Yii2 | http://extra.local |
| Laravel | https://extra.new |

Add to `/etc/hosts`:

```
127.0.0.1 extrasport.local devision.local
127.0.0.1 extra.local piter.extra.local matros.extra.local
127.0.0.1 extra.new piter.extra.new matros.extra.new de-vision.new
```

## Databases

| Stack | Container | Volume | Databases |
|-------|-----------|--------|-----------|
| WordPress deploy | `extra_mariadb` | `extradocker_wp_db_data` | `extra` (user `extra`) |
| Legacy dev | `legacy_mariadb` | `legacy_db_data` | `extra` (Yii2), `extra_new` (Laravel) — from `legacy/db/` |

Legacy DB is on port **3307** (host) and is **not** shared with WordPress.

## Services

| Container | Stack | Role |
|-----------|-------|------|
| `extra_wordpress` | prod + dev | WordPress 7.1 FPM |
| `extra_nginx` | prod + dev | WP reverse proxy (:80/:443) |
| `extra_mariadb` | prod + dev | MariaDB 11 |
| `extra_phpmyadmin` | prod + dev | :8081 |
| `legacy_mariadb` | dev only | Yii2 + Laravel DB (:3307) |
| `legacy_php`, `legacy_laravel`, `legacy_nginx`, `legacy_node` | dev only | Yii2 + Laravel apps |

## Environment

Copy [deploy/.env.example](deploy/.env.example) to `.env` in repo root.
