# Progress — extra.docker

## 2026-08-31 (Today)

### Completed
- [x] Commit + push `58acec4` — membership plans page, nav, inner page polish
- [x] `/card/type/` — rewrite route, view, demo plans (1/3/6/12 мес)
- [x] Membership plan cards — video bg, centered typography, CTA layout
- [x] Membership order modals + REST/form handler (`membership_cards`, `plan_title`)
- [x] Nav active state + link «Абонементы и цены» → `/card/type/`
- [x] Mobile nav: dropdown indent, burger до `xl` (1280px), callback button spacing
- [x] Header: overlay `z-index: 1`, relative + fixed-on-scroll
- [x] Breadcrumbs removed from inner views
- [x] Section spacing (`page-section__inner`, card-choice, membership responsive)
- [x] Theme assets: amenity icons, logo-short, card-bg videos

### Previously pushed (same branch)
- [x] `cd8bd8e` — views/sections/components refactor, share pages
- [x] `f635c70` — per-club rules/slugs, map balloon, REST metadata
- [x] `b41929f` — per-site club admin, branding, carousel header fix
- [x] `96a87cc` — REST lead handling, Yii2 permalinks, forms

### Not committed yet
- `memory-bank/activeContext.md`, `memory-bank/progress.md` (this update)

### Blockers
- Нет

---

## 2026-08-29
- [x] Smoke-test extrasport.local + devision.local (30/32 pass)
- [x] Rules per club, slug refactor (`extrasport` / `devision`)
- [x] Admin «Клуб», multiple form emails, admin branding
- [x] Map.js per-club balloon fix
- [x] `docs/WORDPRESS_UPDATE.md`

---

## Phase Summary — WordPress Migration (`feature/wordpress`)

| Phase | Description | Status | Commit |
|-------|-------------|--------|--------|
| 1–2 | WP setup, Multisite, front-page scaffold | ✅ | `PHASE1_2_COMPLETE.md` |
| 2.5 | Vite + Tailwind CSS v4 | ✅ | `6374c02` |
| 3 | Layout: header/footer, modals, chat | ✅ | `a4f950a` |
| 4 | Front page sections | ✅ | `a4f950a` |
| 5 | JS modules + media | ✅ | `16b774e` |
| 6 | Rules, forms, CPT pages, multisite options | ✅ | `51bb03d` |
| 6+ | Per-site club options, domain mapping | ✅ | `7b337a3` |
| 6++ | Admin club, branding, rules per club | ✅ | `f635c70` |
| 7 | Views refactor, shares, test-drive unify | ✅ | `cd8bd8e` |
| 7+ | Membership page `/card/type/`, nav polish | ✅ | `58acec4` |

---

## Backlog (WordPress)

### High
- [ ] Smoke-test membership page + responsive nav breakpoints
- [ ] PR `feature/wordpress` → main
- [ ] Membership plans CPT/admin (replace demo data)
- [ ] DOCX правил в `assets/docs/`

### Medium
- [ ] Импорт контента Yii2 → WP CPT
- [ ] Privacy / legal pages
- [ ] Cleanup legacy assets in theme `assets/`

### Low / Final phase
- [ ] Timer-акция admin + popup
- [ ] Present video popup admin
- [ ] WordPress 7.1.x update (см. `docs/WORDPRESS_UPDATE.md`)
- [ ] Production deploy

---

## Legacy Tracks (paused)

### Laravel migration (2026-06-12)
- HomeController, HeroDTO — см. `laravel/docs/ai/`
- Не синхронизировано с текущим WordPress-треком

*Last updated: 2026-08-31*
