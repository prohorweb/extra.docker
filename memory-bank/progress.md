# Progress — extra.docker

## 2026-09-01 (Today)

### Completed
- [x] Commit + push `d5a7cd6` — trainers section, roster sync, filters, service page polish
- [x] CPT `trainer` — archive `/trainers/`, singles `/trainers/{slug}/`
- [x] Taxonomy `trainer_direction` — slugs `1` (персональные), `2` (групповые)
- [x] Production roster sync — 26 published; directions v5/v7; legacy slug merge
- [x] Admin: direction checkboxes; hidden excerpt/post attributes for trainer CPT
- [x] Filter logic — unmarked trainers only in «Все направления»
- [x] Sort — featured image first (`WP_Query` + `posts_clauses`; `get_posts` не работает в WP 6.4)
- [x] Trainer cards — `object-top`, square aspect, `extrasport-trainer-card` size
- [x] Media cleanup — orphan trainer attachments, banner/thumb dedupe on sync
- [x] Redirects + nginx: `/es/command/` → `/trainers/`
- [x] Parallax — привязка к секции; «Другие услуги клуба» по высоте контента
- [x] Personal training service page — trainers block with filter

### Previously pushed (same branch, recent)
- [x] `17bae23` — club overview page, about-club routing
- [x] `4eb42d0` — hierarchical services CPT, nested URLs
- [x] `58acec4` — membership `/card/type/`, nav polish
- [x] `cd8bd8e` — views/sections/components refactor, shares

### Not committed yet
- `memory-bank/activeContext.md`, `memory-bank/progress.md` (this update)

### Blockers
- Нет

---

## 2026-08-31
- [x] Commit + push `58acec4` — membership plans page, nav, inner page polish
- [x] `/card/type/` — rewrite route, view, demo plans
- [x] Mobile nav, header scroll, breadcrumbs removed from inner views

---

## 2026-08-29
- [x] Smoke-test extrasport.local + devision.local (30/32 pass)
- [x] Rules per club, slug refactor (`extrasport` / `devision`)
- [x] Admin «Клуб», map balloon fix

---

## Phase Summary — WordPress Migration (`feature/wordpress`)

| Phase | Description | Status | Commit |
|-------|-------------|--------|--------|
| 1–6 | WP setup, Multisite, layout, forms | ✅ | `51bb03d` |
| 6+ | Per-site club, branding, rules | ✅ | `f635c70` |
| 7 | Views refactor, shares, test-drive | ✅ | `cd8bd8e` |
| 7+ | Membership `/card/type/` | ✅ | `58acec4` |
| 8 | Hierarchical services CPT | ✅ | `4eb42d0` |
| 8+ | Club overview page | ✅ | `17bae23` |
| 9 | Trainers CPT, roster, filters | ✅ | `d5a7cd6` |

---

## Backlog (WordPress)

### High
- [ ] Smoke-test trainers filters + service parallax block
- [ ] PR `feature/wordpress` → main
- [ ] News, Events, Jobs pages
- [ ] Membership plans CPT/admin (replace demo data)

### Medium
- [ ] Trainer photos — дозагрузка/синхронизация оставшихся
- [ ] Импорт контента Yii2 → WP CPT
- [ ] DOCX правил в `assets/docs/`

### Low / Final phase
- [ ] Timer-акция admin + popup
- [ ] WordPress update (см. `docs/WORDPRESS_UPDATE.md`)
- [ ] Production deploy
- [ ] Cleanup legacy assets + `wordpress/wp-content/languages/` decision

---

## Legacy Tracks (paused)

### Laravel migration (2026-06-12)
- HomeController, HeroDTO — см. `laravel/docs/ai/`
- Не синхронизировано с текущим WordPress-треком

*Last updated: 2026-09-01*
