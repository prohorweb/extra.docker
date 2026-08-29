# Progress — extra.docker

## 2026-08-29 (Today)

### Completed
- [x] Заголовок модалки правил per-club (`extrasport_get_rules_modal_title()`)
- [x] `rules_modal_title` в реестре клубов, sync version 4
- [x] Полный текст правил De-vision — `inc/rules/devision.php` (58 пунктов, Yii2 matros)
- [x] Slug refactor: только `extrasport` + `devision` (legacy `piter`/`matros` → map)
- [x] Переименование rules: `piter.php` → `extrasport.php`, `matros.php` → `devision.php`
- [x] Admin «Клуб»: контакты, часы, form emails (multi), соцсети; timer/video убраны из UI
- [x] Multiple email recipients для форм
- [x] Per-site admin branding (orange ExtraSport / green De-vision)
- [x] Carousel/header scroll-lock fix
- [x] Club contact data (реальные телефоны, адреса, email)
- [x] `docs/WORDPRESS_UPDATE.md`

### Not committed yet
- Изменения выше + `WORDPRESS_SETUP.md` — локально, last push `b41929f`

### Blockers
- Нет

---

## 2026-08-29 (earlier, pushed `b41929f`)
- [x] Per-site club admin page
- [x] Admin branding CSS
- [x] Carousel header fix (scroll-state + carousel scroll lock)

## 2026-08-29 (pushed `96a87cc`)
- [x] REST `/lead` с honeypot, nonce, timestamp
- [x] CPT `lead`
- [x] Rules lazy REST fetch
- [x] Analytics via cookie-consent event
- [x] Vite prod build без sourcemaps

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
| 6++ | Admin club, branding, rules per club, slug refactor | 🔄 uncommitted | — |

---

## Backlog (WordPress)

### High
- [ ] Commit + push текущих изменений
- [ ] Smoke-test extrasport.local + devision.local
- [ ] DOCX правил в `assets/docs/`
- [ ] Map.js bugfix

### Medium
- [ ] Импорт контента Yii2 → WP CPT
- [ ] Privacy / legal pages
- [ ] Cleanup untracked legacy assets (CSS/JS/images)
- [ ] PR `feature/wordpress` → main

### Low / Final phase
- [ ] Timer-акция admin + popup
- [ ] Present video popup admin
- [ ] WordPress 7.1.x update (см. `docs/WORDPRESS_UPDATE.md`)
- [ ] Production deploy

---

## Legacy Tracks (paused)

### Laravel migration (2026-06-12)
- HomeController, HeroDTO, x-sections.hero — см. `laravel/docs/ai/`
- Не синхронизировано с текущим WordPress-треком

*Last updated: 2026-08-29 21:20*
