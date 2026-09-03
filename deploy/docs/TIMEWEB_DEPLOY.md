# Timeweb Cloud — production deploy

## 0. Безопасность

- **Не храните и не публикуйте** root-пароль, IP, SSH-ключи и содержимое `.env` в чатах, тикетах и логах CI.
- Если секреты уже попали в переписку или лог — **считайте их скомпрометированными**: смените root-пароль в панели Timeweb, перевыпустите SSH-ключи, обновите пароли в `.env` и GitHub Secrets.
- На prod используйте отдельного deploy-пользователя с ключом (без пароля root в CI).
- `.env` **не коммитить**; доступ к серверу — только по SSH-ключу.

## 1. Server

- Ubuntu 22.04+ VPS, Docker + Docker Compose plugin
- DNS `A`/`AAAA` для production-доменов (пример: `extrasport.ru`, `de-vision.ru`; staging: `extra.example.com`, `devision.example.com`)
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
nano .env   # prod passwords + domains — только на сервере, не в git
```

Production `.env` (пример):

```env
WORDPRESS_DB_NAME=extra
WORDPRESS_DB_USER=extra
WORDPRESS_DB_PASSWORD=<strong>
MARIADB_ROOT_PASSWORD=<strong>
WORDPRESS_DOMAIN_CURRENT_SITE=extrasport.ru
WORDPRESS_COOKIE_DOMAIN=.extrasport.ru
WORDPRESS_DEBUG=false
NGINX_SSL_DIR=/etc/letsencrypt/live/extrasport.ru
HEALTHCHECK_HOST_MAIN=extrasport.ru
HEALTHCHECK_HOST_SECOND=de-vision.ru
```

Staging на общем родительском домене (пример):

```env
WORDPRESS_DOMAIN_CURRENT_SITE=extra.example.com
WORDPRESS_COOKIE_DOMAIN=.example.com
NGINX_SSL_DIR=/etc/letsencrypt/live/extra.example.com
HEALTHCHECK_HOST_MAIN=extra.example.com
HEALTHCHECK_HOST_SECOND=devision.example.com
```

## 4. Nginx + TLS

```bash
cp deploy/nginx.production.conf.example nginx.conf
# отредактировать server_name под ваши домены
```

### Первый выпуск сертификата

Пока **ничего не слушает 80/443** (stack ещё не поднят или nginx остановлен):

```bash
certbot certonly --standalone \
  -d extrasport.ru -d de-vision.ru \
  --agree-tos -m admin@example.com
```

Укажите `NGINX_SSL_DIR` на каталог `live/<первый-домен>/` из вывода certbot.

### Автопродление + reload nginx

На Ubuntu `certbot` ставит **systemd timer** (`certbot.timer`). Проверка:

```bash
systemctl status certbot.timer
systemctl list-timers | grep certbot
```

Первый cert выпущен через `--standalone`, поэтому при renew порт 80 должен быть свободен. Hooks в `/etc/letsencrypt/renewal-hooks/`:

**pre-hook** — `/etc/letsencrypt/renewal-hooks/pre/stop-nginx.sh`:

```bash
#!/bin/sh
docker stop extra_nginx 2>/dev/null || true
```

**deploy-hook** — `/etc/letsencrypt/renewal-hooks/deploy/reload-nginx.sh`:

```bash
#!/bin/sh
docker start extra_nginx 2>/dev/null || true
docker exec extra_nginx nginx -s reload 2>/dev/null || true
```

```bash
chmod +x /etc/letsencrypt/renewal-hooks/pre/stop-nginx.sh
chmod +x /etc/letsencrypt/renewal-hooks/deploy/reload-nginx.sh
```

Dry-run продления:

```bash
certbot renew --dry-run
```

## 5. Start stack

```bash
docker compose -f deploy/docker-compose.yml up -d --remove-orphans
```

Volumes `db_data` и `wp_core` создаются автоматически (без `external`).

## 6. Database

Импорт:

```bash
./deploy/scripts/wordpress-import-db.sh /path/to/dump.sql
```

Обновить домены Multisite в БД, если дамп с `.local` — автоматически при CI import, или вручную:

```bash
./deploy/scripts/wordpress-multisite-domain-fix.sh
```

В `.env` на сервере (staging example):

```env
WORDPRESS_DOMAIN_CURRENT_SITE=extra.shiftrunner.ru
HEALTHCHECK_HOST_SECOND=devision.shiftrunner.ru
MIGRATE_FROM_MAIN=extrasport.local
MIGRATE_FROM_SECOND=devision.local
```

Скрипт обновляет `wp_site`, `wp_blogs`, `siteurl`/`home` и делает `wp search-replace` по всем таблицам.

## 7. WordPress admin

1. **Network Admin → Upgrade Network**
2. Опционально: **Re-install ru_RU**

## 8. Post-deploy check

```bash
chmod +x deploy/scripts/post-deploy-healthcheck.sh
./deploy/scripts/post-deploy-healthcheck.sh "${HEALTHCHECK_HOST_MAIN}" "${HEALTHCHECK_HOST_SECOND}"
```

## CD (GitHub Actions)

Workflows: `.github/workflows/ci.yml`, `.github/workflows/deploy.yml`

**Deploy** runs on push to `main` (and manual `workflow_dispatch`) with GitHub Environment **`production`** (manual approval).

### Repository secrets

Храните только в **GitHub Secrets** — не в коде и не в issue/чатах.

| Secret | Description |
|--------|-------------|
| `SSH_HOST` | hostname или IP VPS (не публиковать) |
| `SSH_USER` | deploy user |
| `SSH_PRIVATE_KEY` | private key (no passphrase) |
| `SSH_PORT` | `22` (optional) |
| `DEPLOY_PATH` | `/opt/extra.docker` |
| `DATABASE_DUMP_URL` | HTTPS URL дампа (`.sql` / `.sql.gz`), напр. GitHub Release |
| `DATABASE_DUMP_TOKEN` | optional: PAT для приватного release |

Server must have: git, docker, compose, node/npm, clone of repo, `.env` configured.

### Database import via CI/CD

**Обычный push в `main` — БД не трогает** (только code deploy + backup).

Импорт — только **Run workflow** → Deploy production → ✅ **Import DB**:

1. Загрузите дамп в **GitHub Release** (private repo):

```bash
docker exec extra_mariadb mysqldump -uextra -p'...' extra | gzip > wordpress-extra.sql.gz
gh release create db-v1 wordpress-extra.sql.gz \
  --repo prohorweb/extra.docker \
  --title "WordPress DB snapshot"
```

2. `DATABASE_DUMP_URL` = URL asset из release, напр.  
   `https://github.com/prohorweb/extra.docker/releases/download/db-v1/wordpress-extra.sql.gz`

3. `DATABASE_DUMP_TOKEN` = fine-grained PAT (`Contents: Read`) или classic `ghp_...`

4. Actions → **Deploy production** → Run workflow → **Import DB** ✅ → branch `main` (или `feature/wordpress` до merge)

Скрипт: скачивает dump → import → **полная миграция доменов** (`wp_site`, `wp_blogs`, `siteurl`/`home`, `wp search-replace` по всем таблицам) из `.env`:

```env
WORDPRESS_DOMAIN_CURRENT_SITE=extra.shiftrunner.ru
HEALTHCHECK_HOST_SECOND=devision.shiftrunner.ru
MIGRATE_FROM_MAIN=extrasport.local
MIGRATE_FROM_SECOND=devision.local
```

После import: **Network Admin → Upgrade Network**.

**На сервере вручную** (без Actions):

```bash
IMPORT_DB=1 DATABASE_DUMP_URL='https://...' ./deploy/scripts/fetch-and-import-db.sh
```

Deploy script (prod only, no legacy):

```bash
./deploy/scripts/deploy-production.sh
```

Steps: DB backup → `git pull` → `npm run build` → `docker compose -f deploy/docker-compose.yml up -d --remove-orphans` → health-check.

Pre-deploy backup (manual):

```bash
set -a && source .env && set +a
docker exec extra_mariadb mysqldump \
  -u"${WORDPRESS_DB_USER}" -p"${WORDPRESS_DB_PASSWORD}" "${WORDPRESS_DB_NAME}" > backup.sql
```

## Dev-only services

phpMyAdmin и legacy — только с dev overlay:

```bash
docker compose -f deploy/docker-compose.yml -f deploy/docker-compose.dev.yml up -d
```

phpMyAdmin: `127.0.0.1:8081` (не expose на prod).

## Checklist (первый деплой)

1. DNS → VPS
2. `.env` + `nginx.conf` на сервере
3. certbot (первый выпуск) → hooks для renew
4. `docker compose -f deploy/docker-compose.yml up -d` (**prod only**)
5. import DB → multisite domain fix
6. **Upgrade Network**
7. GitHub Environment `production` + secrets → CD
