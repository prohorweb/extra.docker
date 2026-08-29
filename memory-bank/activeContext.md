# Active Context — Yii2 → WordPress Migration

## Current Session (August 29, 2026) — 21:20

### Status
**In Progress**: Финализация multisite-клубов (extrasport + devision), правила, админ «Клуб»

### Branch & Deploy
- **Branch:** `feature/wordpress`
- **Last pushed commit:** `b41929f` — per-site club admin, branding, carousel header fixes
- **Uncommitted work:** slug refactor, rules modal, devision rules content, admin club settings, multi-email, docs

### Architecture (active track)
| Домен | Blog ID | Slug | rules_slug |
|-------|---------|------|------------|
| `extrasport.local` | 1 | `extrasport` | `extrasport` |
| `devision.local` | 2 | `devision` | `devision` |

**Stack:** WordPress 6.4 Multisite + theme `extrasport` + Tailwind v4 + Vite + native JS  
**Strategy:** Yii2 (`frontend/`) — только референс. Bootstrap/jQuery не переносим.

---

### Last Action (this session)
- [x] Заголовок модалки правил per-club: `extrasport_get_rules_modal_title()`
  - ExtraSport: `ПРАВИЛА СПОРТИВНОГО КЛУБА «ЭКСТРА СПОРТ» ТК «ПИТЕР»`
  - De-vision: `ПРАВИЛА СПОРТИВНОГО КЛУБА DE-VISION`
- [x] Полный текст правил De-vision в `inc/rules/devision.php` (58 пунктов, из Yii2 matros variant)
- [x] Slug refactor: `piter` → `extrasport`, `matros` → `devision` (legacy map сохранён)
- [x] `rules_modal_title` в реестре клубов, `EXTRASPORT_CLUB_DATA_VERSION = 4`

### Previously completed (feature/wordpress)
- [x] ФАЗА 2.5: Vite + Tailwind CSS v4
- [x] ФАЗА 3–4: Layout + front page (carousel, video, shares, map, subscribe)
- [x] ФАЗА 5: JS-модули, cookie-consent, present-video, timer
- [x] ФАЗА 6: rules lazy REST, form handlers, CPT archives/singles
- [x] Multisite per-site options (`extrasport_club`, domain routing)
- [x] Admin «Клуб» (`inc/admin-club-settings.php`) — контакты, часы, emails, соцсети
- [x] Per-site admin branding (orange/green)
- [x] Carousel/header scroll-lock bug fix
- [x] Multiple form email recipients
- [x] Club contact data sync (ExtraSport + De-vision реальные контакты)
- [x] `docs/WORDPRESS_UPDATE.md` — стратегия обновления WP до 7.1.x

---

### Key Files
| File | Role |
|------|------|
| `inc/multisite.php` | Club registry, slugs, sync, domain resolution |
| `inc/admin-club-settings.php` | wp-admin «Клуб» |
| `inc/rules.php` + `inc/rules/*.php` | Rules modal helpers + content |
| `template-parts/layout/modal-rules.php` | Rules modal UI |
| `inc/form-handlers.php` | Lead storage + wp_mail |
| `assets/src/modules/scroll-state.js` | Header fixed state during carousel |
| `assets/src/modules/carousel.js` | Wheel nav + scroll lock |
| `WORDPRESS_SETUP.md` | Setup & phase status |
| `docs/WORDPRESS_UPDATE.md` | WP core update plan |

---

### Known Issues / Deferred
- **Map footer bug** — возможна проблема в `map.js` (из ранней сессии)
- **Timer-акция + present video popup** — admin UI убран, перенесён на финальную фазу
- **rules-devision.docx / rules-extrasport.docx** — кнопка «Скачать» без файлов в `assets/docs/`
- **De-vision rules п.1** — текст «Экстра спорт» из legacy; брендинг в заголовке уже DE-VISION
- **Large untracked asset dump** — legacy CSS/images/JS в `assets/` (решить: commit или cleanup)

---

### Next Planned (priority order)

#### Сразу
1. **Commit** незакоммиченные изменения на `feature/wordpress` (slug refactor, rules, admin, docs)
2. **Smoke-test** оба домена: модалка правил, формы, carousel/header, контакты в footer
3. **DOCX правил** — положить `assets/docs/rules-extrasport.docx` и `rules-devision.docx`

#### Ближайшие задачи
4. **Map.js** — проверить и починить карту/контакты на главной
5. **Контент** — импорт баннеров, акций, услуг из Yii2 БД или ручное наполнение CPT
6. **Внутренние страницы** — privacy, legal, blog (если нужны на prod)
7. **PR** — `feature/wordpress` → main с test plan

#### Финальная фаза (отложено)
8. Admin: timer-акция, present video popup, email_timer
9. WordPress core update по `docs/WORDPRESS_UPDATE.md` (ждать 7.1.1)
10. Production deploy checklist

---

### Legacy / Parallel Tracks (не активны)
- **Laravel migration** (`laravel/`) — отдельный трек, memory bank ранее описывал HomeController/HeroDTO
- **Yii2** (`frontend/`, `extra_php`) — источник контента, постепенно выводится из эксплуатации
