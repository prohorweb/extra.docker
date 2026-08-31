# Active Context — Yii2 → WordPress Migration

## Current Session (August 31, 2026)

### Status
**In Progress**: Страница абонементов, полировка навигации и inner pages на `feature/wordpress`

### Branch & Deploy
- **Branch:** `feature/wordpress`
- **Last pushed commit:** `58acec4` — membership plans page + mobile nav refinements
- **Previous:** `cd8bd8e` — theme views refactor + share pages polish
- **Uncommitted:** только `memory-bank/` (docs)

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
- Sections: `sections/*` (front page blocks)
- Components: `components/cards/*`, `components/modals/*`
- Assets build: `npm run build` in theme dir → `assets/dist/` (gitignored, tracked in repo from prior commits)

---

### Last Action (this session)
- [x] Commit + push `58acec4`: `/card/type/` membership page, plan cards, order modals, forms
- [x] `inc/card-type.php` — rewrite `/card/type/`, demo plans, amenities helpers, `extrasport_get_card_type_url()`
- [x] `views/card-type/index.php` — amenities grid + 2×2 plan cards + test-drive
- [x] `components/cards/membership-plan.php` — video bg, centered row (month/logo/price), CTA below
- [x] `components/modals/membership-order.php` — subscribe form with `plan_title`
- [x] Assets: `assets/images/card-choice-services-*.svg`, `logo-short.svg`, `assets/video/card-bg-*.mp4`
- [x] Nav: `inc/nav.php` active helpers; «Абонементы и цены» → `/card/type/`
- [x] Header: mobile menu до `xl` (1280px), dropdown indent, callback `me-4`, overlay `z-index: 1`
- [x] Header scroll: `relative` без скролла, `site-header--fixed` при прокрутке
- [x] Breadcrumbs убраны со всех inner views
- [x] `page-section__inner` — уменьшенные `py` / `mb`; card-type spacing polish
- [x] Membership card responsive: compact до `lg`, md-only fix для «12 месяцев»

### Previously completed (feature/wordpress)
- [x] Theme refactor: `views/`, `sections/`, `components/` (`cd8bd8e`)
- [x] Shares archive/single, cards, seed, admin meta
- [x] Unified test-drive section (`sections/test-drive.php`)
- [x] Per-club rules/slugs, map balloon, REST metadata (`f635c70`)
- [x] Admin «Клуб», branding, carousel scroll-lock fix
- [x] Phases 1–6: Vite, layout, front page, JS modules, forms, multisite

---

### Key Files
| File | Role |
|------|------|
| `inc/template-router.php` | Single entry router → views |
| `inc/card-type.php` | `/card/type/` route + plans/amenities |
| `inc/nav.php` | Active nav helpers |
| `layouts/header.php` | Desktop + mobile nav |
| `views/card-type/index.php` | Membership plans page |
| `components/cards/membership-plan.php` | Plan card UI |
| `components/modals/membership-order.php` | Order modal per plan |
| `views/share/*` | Shares archive/single |
| `assets/src/input.css` | Tailwind components (header, cards, sections) |
| `assets/src/modules/scroll-state.js` | Header fixed on scroll |
| `inc/form-handlers.php` | Leads + membership form emails |
| `inc/multisite.php` | Club registry, slugs |

---

### Known Issues / Deferred
- **Membership plans** — demo data in `extrasport_get_membership_plans()`; CPT/admin not wired yet
- **DOCX правил** — `assets/docs/rules-*.docx` отсутствуют
- **Timer-акция + present video popup** — admin UI отложен
- **Large legacy asset dump** — старые CSS/images/JS в `assets/` (cleanup TBD)
- **Permalinks** — после деплоя `/card/type/` может потребовать flush (Settings → Permalinks или `extrasport_maybe_flush_card_type_rewrite`)

---

### Next Planned (priority order)

#### Сразу
1. **Smoke-test** `/card/type/`, shares, mobile nav (768px / 1024px / 1280px)
2. **PR** — `feature/wordpress` → main с test plan

#### Ближайшие задачи
3. **Membership CPT/admin** — заменить demo plans
4. **Контент** — импорт баннеров, акций, услуг из Yii2
5. **DOCX правил** в `assets/docs/`
6. **Privacy / legal / blog** inner pages при необходимости

#### Финальная фаза (отложено)
7. Admin: timer-акция, present video popup
8. WordPress core update (`docs/WORDPRESS_UPDATE.md`)
9. Production deploy checklist
10. Cleanup untracked legacy assets

---

### Legacy / Parallel Tracks (не активны)
- **Laravel migration** (`laravel/`) — отдельный трек
- **Yii2** (`frontend/`, `extra_php`) — источник контента и вёрстки
