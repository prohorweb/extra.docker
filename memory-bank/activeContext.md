# Active Context — extra.docker

## Current Session (September 3, 2026)

### Status
**Deploy restructure complete** — WordPress 7.1 production stack + optional legacy dev overlay. Theme decoupled from Yii runtime.

### Branch
- **Branch:** `feature/wordpress`
- **Uncommitted:** restructure (`deploy/`, compose split, legacy untracked, memory-bank, nginx proxy)

---

## Architecture (актуально)

### Repo layout
| Path | In git | Role |
|------|--------|------|
| `deploy/` | ✅ | WP theme, config, uploads, compose, import scripts |
| `legacy/` | ❌ gitignored | Yii2/Laravel archive, local dev only |
| Root | ✅ | `nginx.conf`, `nginx.legacy-proxy.conf`, compose include |

### WordPress Multisite
| Domain | Slug | Stack |
|--------|------|-------|
| `extrasport.local` | extrasport | WP 7.1, theme `extrasport` |
| `devision.local` | devision | same network |

**Theme:** Tailwind v4 + Vite + native JS. Yii2 — только референс вёрстки (legacy).

### Docker stacks

**Deploy only:**
```bash
docker compose -f deploy/docker-compose.yml up -d
```

**Dev (+ legacy):**
```bash
docker compose -f deploy/docker-compose.yml -f deploy/docker-compose.dev.yml up -d --build
```

### Databases (isolated)
| Container | DB | Purpose |
|-----------|-----|---------|
| `extra_mariadb` | `extra` | WordPress only (`extra` / `extra123`) |
| `legacy_mariadb` | `extra`, `extra_new` | Yii2 + Laravel (dev, port 3307) |

Volumes: `db_data`, `wp_core` (auto-created). Legacy dev: `deploy_legacy_db_data`.

### Services
| Container | Port | Notes |
|-----------|------|-------|
| `extra_nginx` | 80/443 | WP multisite |
| `extra_wordpress` | 9000 | FPM |
| `extra_phpmyadmin` | **8081** | WP DB `extra` |
| `legacy_nginx` | 8080/8443/8090 | direct legacy access |
| `legacy_*` | — | Yii/Laravel, dev overlay only |

Legacy on port 80: `nginx.legacy-proxy.conf` → `legacy_nginx` (extra.local, extra.new).

---

## Session changes (2026-09-03)

- [x] Split compose: `deploy/docker-compose.yml` + `deploy/docker-compose.dev.yml`
- [x] WP DB isolated: `extra` on `extradocker_wp_db_data`
- [x] Legacy DB isolated: `legacy_mariadb` + `import-legacy.sh`
- [x] `legacy/` removed from git tracking
- [x] Theme: removed Yii runtime (yii-db, seeds from prod load path → `legacy/wordpress-import/`)
- [x] Redirects + content-html kept for prod
- [x] `extra.local` on :80 via proxy; Yii nginx fastcgi fix
- [x] `legacy/common/config/main-local.php` → `DB_HOST=legacy-db`
- [x] phpMyAdmin unchanged at **localhost:8081**

---

## Post-deploy admin steps (WP)

1. **Network Admin → Upgrade Network** — after core 7.1 (DB schema).
2. **Re-install 7.1–ru_RU** — optional once for Russian admin UI (`WORDPRESS_LANG` already set).

---

## Key files

| File | Role |
|------|------|
| `deploy/docker-compose.yml` | WP production stack |
| `deploy/docker-compose.dev.yml` | Legacy overlay |
| `deploy/wp-config.php` | Multisite, WPLANG ru_RU |
| `deploy/legacy-db-init/import-legacy.sh` | Legacy SQL import |
| `nginx.conf` | WP vhosts |
| `nginx.legacy-proxy.conf` | Dev legacy proxy on :80/:443 |
| `legacy/nginx.conf` | Yii/Laravel internal routing |
| `deploy/wp-content/themes/extrasport/` | Active theme |
| `deploy/docs/TIMEWEB_DEPLOY.md` | Timeweb production runbook |

---

## Next steps

1. Commit restructure + memory-bank
2. Smoke-test after **Upgrade Network**
3. Production deploy checklist
4. Optional: automate `ru_RU` language pack on container start

---

## Deferred / not active

- Laravel strangler (`legacy/laravel/`) — dev reference only
- `legacy/wordpress-import/` — one-time import scripts, not loaded in prod
- Membership CPT admin, timer popup — backlog
