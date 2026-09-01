# Active Context — Yii2 → WordPress Migration

## Current Session (September 1, 2026)

### Status
**In Progress**: Тренеры, услуги, клуб — inner pages на `feature/wordpress`

### Branch & Deploy
- **Branch:** `feature/wordpress`
- **Last pushed commit:** `d5a7cd6` — trainers section, roster sync, filters, service page polish
- **Previous:** `17bae23` — club overview page; `4eb42d0` — hierarchical services CPT
- **Uncommitted:** `memory-bank/` (docs); untracked `wordpress/wp-content/languages/` (WP locale files, не коммитить)

### Architecture (active track)
| Домен | Blog ID | Slug | rules_slug |
|-------|---------|------|------------|
| `extrasport.local` | 1 | `extrasport` | `extrasport` |
| `devision.local` | 2 | `devision` | `devision` |

**Stack:** WordPress 6.4 Multisite + theme `extrasport` + Tailwind v4 + Vite + native JS  
**Strategy:** Yii2 (`frontend/`) — только референс. Bootstrap/jQuery не переносим.

**Theme structure (Laravel-like):**
- Router: `index.php` → `inc/template-router.php` → `views/*`
- Layouts: `layouts/header.php`, `layouts/footer.php`
- Sections: `sections/*` (front page blocks, trainers block)
- Components: `components/cards/*`, `components/modals/*`
- Assets build: `npm run build` in theme dir → `assets/dist/`

---

### Last Action (this session)
- [x] Commit + push `d5a7cd6`: trainers CPT, archive `/trainers/`, singles, filters, roster sync
- [x] `inc/trainers.php` — helpers, direction filter, thumbnail-first sort via `WP_Query` + `posts_clauses`
- [x] `inc/sync-trainers-roster.php` v7 — 26 published trainers, production directions, orphan cleanup
- [x] `inc/seed-trainers.php` — Yii import; `inc/admin-trainer-meta.php` — direction checkboxes
- [x] `views/trainer/index.php`, `show.php`; `sections/trainers/block.php`, `filter.php`
- [x] Trainer cards: `object-top`, `aspect-square`, image size `extrasport-trainer-card`
- [x] Media: `extrasport_cleanup_orphan_trainer_attachments()`, banner/thumb dedupe on sync
- [x] Redirects: `/es/command/` → `/trainers/`; nginx location for legacy Yii trainers list
- [x] Personal training page embeds trainers block with filter
- [x] Parallax: section-relative scroll in `parallax.js`; «Другие услуги» без `page-section--h-75`
- [x] Club overview (`17bae23`), hierarchical services (`4eb42d0`)

### Previously completed (feature/wordpress)
- [x] Membership `/card/type/` (`58acec4`)
- [x] Views refactor, shares, test-drive (`cd8bd8e`)
- [x] Per-club rules/slugs, map balloon (`f635c70`)
- [x] Phases 1–6: Vite, layout, front page, JS modules, forms, multisite

---

### Trainers — production state (Piter, blog 1)
| Filter | Count | Notes |
|--------|-------|-------|
| Все | 26 | `menu_order` = roster index × 10 |
| Персональные `[1]` | 10 | only marked trainers |
| Групповые `[2]` | 7 | only marked trainers |
| Без отметок | 16 | only in «Все направления» |

**Sort:** trainers with featured image first, then `menu_order`.

**Useful commands:**
```bash
# Re-sync roster
docker exec extra_wordpress php -r "
define('WP_USE_THEMES', false);
require '/var/www/html/wp-load.php';
if (is_multisite()) switch_to_blog(1);
delete_option('extrasport_trainers_roster_version');
extrasport_sync_trainers_roster(true);
"
```

---

### Key Files
| File | Role |
|------|------|
| `inc/trainers.php` | Trainers query, filter, sort, archive SEO |
| `inc/sync-trainers-roster.php` | Production roster sync |
| `inc/seed-trainers.php` | Yii trainer import |
| `inc/admin-trainer-meta.php` | Direction checkboxes metabox |
| `inc/post-types.php` | CPT `trainer` |
| `inc/taxonomies.php` | `trainer_direction` (hidden tag UI) |
| `inc/redirects.php` | Legacy trainer slugs + `/es/command/` |
| `views/trainer/*` | Archive + single |
| `sections/trainers/*` | Reusable list + filter |
| `components/cards/trainer.php` | Trainer card |
| `assets/src/modules/parallax.js` | Section-bound parallax |
| `views/service/show.php` | Service single + «Другие услуги» |
| `inc/services.php` | Service helpers, parallax bg |
| `views/club/index.php` | Club overview |

---

### Known Issues / Deferred
- **Trainer photos** — не у всех есть миниатюра (placeholder logo); часть фото 330×330
- **Membership plans** — demo data in `extrasport_get_membership_plans()`; CPT/admin not wired
- **DOCX правил** — `assets/docs/rules-*.docx` отсутствуют
- **Timer-акция + present video popup** — admin UI отложен
- **`wordpress/wp-content/languages/`** — локаль RU, не в git

---

### Next Planned (priority order)

#### Сразу
1. **Smoke-test** `/trainers/`, filters `?filter=1|2`, service singles, parallax «Другие услуги»
2. **PR** — `feature/wordpress` → main

#### Ближайшие задачи
3. **News, Events, Jobs** — inner pages (about submenu)
4. **Membership CPT/admin** — заменить demo plans
5. **Контент** — оставшийся импорт из Yii2
6. **DOCX правил** в `assets/docs/`

#### Финальная фаза (отложено)
7. Admin: timer-акция, present video popup
8. WordPress core update (`docs/WORDPRESS_UPDATE.md`)
9. Production deploy checklist
10. Cleanup untracked legacy assets

---

### Legacy / Parallel Tracks (не активны)
- **Laravel migration** (`laravel/`) — отдельный трек
- **Yii2** (`frontend/`, `extra_php`) — источник контента и вёрстки
