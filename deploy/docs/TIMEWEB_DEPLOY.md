# Timeweb Cloud — production deploy

## 1. Server

- Ubuntu 22.04+ VPS, Docker + Docker Compose plugin
- DNS `A`/`AAAA` для `extrasport.ru`, `de-vision.ru` (и `www` при необходимости)
- Firewall: **80**, **443** (не открывать 8081/phpMyAdmin на prod)

## 2. Checkout

```bash
git clone git@github.com:prohorweb/extra.docker.git /opt/extra.docker
cd /opt/extra.docker
git checkout main
```

## 3. Environment

```bash
cp deploy/.env.example .env
nano .env   # prod passwords + domains
```

Production `.env`:

```env
WORDPRESS_DB_NAME=extra
WORDPRESS_DB_USER=extra
WORDPRESS_DB_PASSWORD=<strong>
MARIADB_ROOT_PASSWORD=<strong>
WORDPRESS_DOMAIN_CURRENT_SITE=extrasport.ru
WORDPRESS_COOKIE_DOMAIN=.extrasport.ru
WORDPRESS_DEBUG=false
NGINX_SSL_DIR=/etc/letsencrypt/live/extrasport.ru
```

## 4. Nginx + TLS

```bash
cp deploy/nginx.production.conf.example nginx.conf
# certbot on host, then set NGINX_SSL_DIR or mount fullchain.pem/privkey.pem
```

Certbot (пример):

```bash
certbot certonly --standalone -d extrasport.ru -d www.extrasport.ru -d de-vision.ru -d www.de-vision.ru
```

## 5. Start stack

```bash
docker compose -f deploy/docker-compose.yml up -d --remove-orphans
```

Volumes `db_data` и `wp_core` создаются автоматически (без `external`).

## 6. Database

```bash
./deploy/scripts/wordpress-import-db.sh /path/to/dump.sql
```

Обновить домены Multisite в БД, если дамп с `.local`.

## 7. WordPress admin

1. **Network Admin → Upgrade Network**
2. Опционально: **Re-install ru_RU**

## 8. Post-deploy check

```bash
chmod +x deploy/scripts/post-deploy-healthcheck.sh
./deploy/scripts/post-deploy-healthcheck.sh extrasport.ru de-vision.ru
```

## CD (GitHub Actions)

```bash
git pull
docker compose -f deploy/docker-compose.yml up -d --remove-orphans
./deploy/scripts/post-deploy-healthcheck.sh
```

Pre-deploy backup:

```bash
docker exec extra_mariadb mysqldump -u"${WORDPRESS_DB_USER}" -p"${WORDPRESS_DB_PASSWORD}" "${WORDPRESS_DB_NAME}" > backup.sql
```

## Dev-only services

phpMyAdmin и legacy — только с dev overlay:

```bash
docker compose -f deploy/docker-compose.yml -f deploy/docker-compose.dev.yml up -d
```

phpMyAdmin: `127.0.0.1:8081` (не expose на prod).
