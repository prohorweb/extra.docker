# Progress — extra.docker

## 2026-09-03 — Deploy restructure & infra

### Completed
- [x] Repo split: `deploy/` (WP) + `legacy/` (local archive, **untracked**)
- [x] `deploy/docker-compose.yml` — WP 7.1 + nginx + MariaDB + phpMyAdmin
- [x] `deploy/docker-compose.dev.yml` — legacy Yii2/Laravel + **separate** `legacy_mariadb`
- [x] WP database: single `extra` / user `extra` on `extradocker_wp_db_data`
- [x] Legacy databases: `extra` + `extra_new` on `deploy_legacy_db_data` via `import-legacy.sh`
- [x] phpMyAdmin: **http://localhost:8081** → WP MariaDB
- [x] Legacy MariaDB host port **3307**
- [x] `nginx.legacy-proxy.conf` — `extra.local` / `extra.new` on :80/:443 (no redirect to extrasport)
- [x] Legacy Yii: DB host `legacy-db`, nginx fastcgi fix for `index.php`
- [x] Theme prod cleanup: no yii-db, no seed/migration in `functions.php`
- [x] Import toolkit archived → `legacy/wordpress-import/`
- [x] `.gitignore`: `legacy/` entire; uploads in deploy tracked
- [x] WP data migrated to deploy volume; sites 200 (extrasport, devision, extra.local)
- [x] Memory-bank: `deploy-workflow.md`, updated activeContext

### Admin follow-up (manual)
- [ ] **Upgrade Network** — multisite DB after WP 7.1
- [ ] Optional: **Re-install ru_RU** — Russian wp-admin language pack

### Not committed
- Full restructure diff, memory-bank, nginx.legacy-proxy.conf, legacy-db-init

---

## 2026-09-01 — Trainers & inner pages

- [x] Trainers CPT, roster sync, filters (`d5a7cd6`)
- [x] Club overview, hierarchical services
- [x] Redirects `/es/command/` → `/trainers/`

---

## Phase Summary — WordPress Migration

| Phase | Description | Status |
|-------|-------------|--------|
| 1–6 | WP setup, Multisite, layout, forms | ✅ |
| 7–9 | Inner pages, trainers, services, club | ✅ |
| 10 | **Deploy restructure, WP 7.1, DB split** | ✅ (uncommitted) |
| 11 | Production deploy + Upgrade Network | 🔄 in progress |

---

## Backlog

### High
- [ ] Commit & PR restructure branch
- [ ] Upgrade Network + smoke-test multisite
- [ ] Update `deploy/docs/WORDPRESS_UPDATE.md` credentials (extra/extra)

### Medium
- [ ] Membership CPT/admin
- [ ] DOCX rules in theme assets

### Low
- [ ] Auto-install ru_RU language files on deploy
- [ ] Remove obsolete `legacy/docker-compose.legacy-import.example.yml`

---

## Legacy tracks (paused)

- Yii2/Laravel — `legacy/`, dev overlay only, not in git
- Laravel migration experiment — not synced with WP track

*Last updated: 2026-09-03*
