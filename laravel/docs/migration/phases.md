# Migration Phases — Detailed Exit Criteria

---

## Phase 1 — Foundation (Frontend) ✅ DONE

### Exit Criteria
- [x] Tailwind CSS v4 configured with `@theme` design tokens
- [x] Design Tokens: colors, typography, spacing, radius, shadows, transitions, z-index
- [x] Core UI Components (`x-ui.*`): button, card, input, label, badge, modal
- [x] Section Components (`x-sections.*`): hero with video background, grid, cta
- [x] Layout Components: `x-layout`, `x-header`, `x-footer`, `x-navigation`
- [x] Alpine.js Architecture: stores (navigation, modals), components
- [x] Responsive breakpoints tested (mobile, tablet, desktop)
- [x] Accessibility basics: focus-visible, ARIA labels, semantic HTML

### Deliverables
- `resources/css/app.css` с полным `@theme`
- `resources/views/components/ui/` — 6+ компонентов
- `resources/views/components/sections/` — 3+ компонентов
- `resources/views/components/layouts/` — 3 компонента
- `resources/js/alpine/stores/` — navigation, modals

---

## Phase 2 — Homepage + Core Backend 🔄 IN PROGRESS

### Exit Criteria
- [ ] Eloquent Models: `Club`, `Setting`, `Service`, `Share`, `Trainer`, `Banner`, `Metro`
- [ ] Database: migrations, seeders, factories для всех моделей
- [ ] Data Layer: `HomepageData`, `HeroData`, `ShareCardData`, `ServiceCardData`, `TrainerCardData`
- [ ] Service Layer: `HomepageService` с методами агрегации данных
- [ ] Controller: `HomeController@index` возвращает `HomepageData`
- [ ] Blade: `pages/home.blade.php` использует section components
- [ ] SEO: `SEOData` для главной страницы, JSON-LD `Organization`
- [ ] Tests: Feature test для homepage (status 200, данные загружены)

### Dependencies
- Phase 1 (components, tokens, Alpine stores)

---

## Phase 3 — Authentication (Laravel Breeze) ⏳ PLANNED

### Exit Criteria
- [ ] `composer require laravel/breeze --dev && php artisan breeze:install`
- [ ] Blade auth views адаптированы под Design System (tokens, components)
- [ ] Email verification работает
- [ ] Password reset работает
- [ ] User profile страница (Filament или Blade)
- [ ] Middleware: `auth`, `verified`, `guest`
- [ ] Tests: Feature tests для login, register, password reset

### Dependencies
- Phase 2 (User model, migrations)

---

## Phase 4 — Static Pages + Contact Forms ⏳ PLANNED

### Exit Criteria
- [ ] Статичные страницы: `about`, `contacts`, `club`, `legal`, `privacy`, `offer`
- [ ] Каждая страница: Controller + Data + Blade + SEOData
- [ ] `ContactController` + `ContactRequest` + `ContactNotification` (Mail + Queue)
- [ ] `CallbackController` + `CallbackRequest` + `CallbackNotification` (Mail + Queue)
- [ ] Alpine компоненты форм: `x-features.forms.contact`, `x-features.forms.callback`
- [ ] Rate limiting: 3/min per IP, honeypot field
- [ ] Tests: Feature tests для форм (validation, success, rate limit)

### Dependencies
- Phase 2 (core backend, mail config)

---

## Phase 5 — Feature Modules (Public) ⏳ PLANNED

### Модули (порядок приоритета)
| Модуль | Контроллер | Страницы | Данные |
|--------|------------|----------|--------|
| **Services** | `ServiceController` | index, show | `ServiceCardData`, `ServiceDetailData` |
| **News** | `NewsController` | index, show | `NewsCardData`, `NewsDetailData` |
| **Shares** | `ShareController` | index, show | `ShareCardData`, `ShareDetailData` |
| **Trainers** | `TrainerController` | index, show | `TrainerCardData`, `TrainerDetailData` |
| **Events** | `EventController` | index, show | `EventCardData`, `EventDetailData` |
| **Programs** | `ProgramController` | index, show | `ProgramCardData`, `ProgramDetailData` |
| **Jobs** | `JobController` | index, show | `JobCardData`, `JobDetailData` |
| **Articles** | `ArticleController` | index, show | `ArticleCardData`, `ArticleDetailData` |

### Exit Criteria (на модуль)
- [ ] Eloquent Model + Migration + Factory + Seeder
- [ ] Data Classes (Card + Detail + Filter)
- [ ] Service Class с `getFeatured()`, `getPaginated(FilterData)`
- [ ] Controller (index, show) + Form Request (filter)
- [ ] Blade Components: `x-features.{module}.card`, `grid`, `detail`
- [ ] Pages: `index.blade.php`, `show.blade.php`
- [ ] SEOData для обеих страниц + JSON-LD (`Service`, `Article`, `Event`, `JobPosting`, `Person`)
- [ ] Filament Resource (admin)
- [ ] Feature Tests (index, show, filter, pagination)

### Dependencies
- Phase 2 (core models, data layer pattern)
- Phase 7 (Media Library для изображений) — параллельно

---

## Phase 6a — Public Forms (Callback, Contact) ⏳ PLANNED

### Exit Criteria
- [ ] `Callback` модель + миграция (name, phone, club_id, consent, processed_at, honeypot)
- [ ] `Contact` модель + миграция (name, email, phone, subject, message, consent, processed_at, honeypot)
- [ ] Form Requests: `CallbackRequest`, `ContactRequest` (validation + sanitization)
- [ ] Mailable: `CallbackNotification`, `ContactNotification` (markdown, queue)
- [ ] Controllers: `CallbackController@store`, `ContactController@store` (JSON response)
- [ ] Alpine компоненты: `x-features.forms.callback`, `x-features.forms.contact`
- [ ] Rate Limiting: `throttle:3,1` per IP, honeypot validation
- [ ] Tests: Feature тесты (validation, success, rate limit, honeypot)

### Dependencies
- Phase 4 (contact forms backend)
- Mail + Queue настроены

---

## Phase 6b — Admin Panel (Filament v3) ⏳ PLANNED

### Exit Criteria
- [ ] Filament v3 установлен, панель `admin` настроена
- [ ] Брендирование: primary color `#c8102e`, font Oswald, dark mode
- [ ] Resources для всех Feature Modules (Phase 5) + Callback/Contact
- [ ] Widgets: StatsOverview (заявки, услуги, тренеры, новости)
- [ ] Media Library интеграция во все ресурсы с изображениями
- [ ] Permissions: `admin`, `editor`, `viewer` роли
- [ ] Navigation Groups: Заявки, Контент, Команда, Настройки, Система
- [ ] Tests: Filament resource testing (CRUD, filters, actions)

### Dependencies
- Phase 6a (модели Callback/Contact должны существовать)
- Phase 5 (модели для resources)

---

## Phase 7 — Media + File Uploads ⏳ PLANNED

### Exit Criteria
- [ ] Spatie Laravel Media Library установлен и настроен
- [ ] `media` диск: local (dev) / S3 (prod)
- [ ] Conversions: `thumb` (400x300), `preview` (800x600), `og` (1200x630)
- [ ] Responsive images: `srcset` через Media Library
- [ ] Video: постер + preload=none, lazy loading
- [ ] Все модели с медиа: `HasMedia` trait + `registerMediaCollections()`
- [ ] Cleanup: неиспользуемые медиа удаляются при удалении модели
- [ ] Tests: Media upload, conversions, deletion

### Dependencies
- Phase 5 (модули используют медиа)

---

## Phase 8 — SEO + Performance ⏳ PLANNED

### Exit Criteria
- [ ] `SEOData` DTO + `x-seo.meta` компонент во всех layout'ах
- [ ] JSON-LD для всех типов страниц (Organization, Service, Article, Event, Person, BreadcrumbList)
- [ ] Canonical URLs на всех страницах
- [ ] OG Tags + Twitter Cards
- [ ] Sitemap (Spatie Laravel Sitemap) — автогенерация + ping
- [ ] Robots.txt (динамический)
- [ ] Performance: Lighthouse CI в GitHub Actions (budget: Perf > 90, A11y > 95, SEO > 95)
- [ ] Fonts: self-hosted variable fonts (Oswald, Roboto, Plus Jakarta Sans) с preload
- [ ] Images: AVIF/WebP, lazy loading, responsive srcset
- [ ] Video: poster, preload=none, mobile optimization
- [ ] JS: code splitting, lazy imports, minimal Alpine payload

### Dependencies
- Phase 5 (все страницы должны иметь SEO)
- Phase 7 (медиа для OG images)

---

## Phase 9 — Strangler Fig + Production ⏳ PLANNED

### Exit Criteria
- [ ] Nginx config: поэтапное переключение маршрутов на Laravel
- [ ] Zero-downtime deployment (blue-green или rolling)
- [ ] Health checks: `/up`, `/health` endpoints
- [ ] Monitoring: Laravel Telescope (dev), Sentry (prod)
- [ ] Backup strategy: DB + Media + Env
- [ ] Rollback procedure документирован и протестирован
- [ ] Load testing: k6 / Artillery (1000 RPS target)
- [ ] Security audit: CSP, HSTS, rate limits, input validation
- [ ] Documentation: runbooks для инцидентов

### Strangler Fig Routing Plan
| Route | Target | Phase |
|-------|--------|-------|
| `/` | Laravel | Phase 2 |
| `/services/*` | Laravel | Phase 5 |
| `/news/*` | Laravel | Phase 5 |
| `/shares/*` | Laravel | Phase 5 |
| `/trainers/*` | Laravel | Phase 5 |
| `/events/*` | Laravel | Phase 5 |
| `/programs/*` | Laravel | Phase 5 |
| `/jobs/*` | Laravel | Phase 5 |
| `/articles/*` | Laravel | Phase 5 |
| `/about` | Laravel | Phase 4 |
| `/contacts` | Laravel | Phase 4 |
| `/club` | Laravel | Phase 4 |
| `/legal` | Laravel | Phase 4 |
| `/privacy` | Laravel | Phase 4 |
| `/offer` | Laravel | Phase 4 |
| `/admin/*` | Laravel | Phase 6b |
| `/*` | Yii2 (legacy) | Until done |

---

## Навигация

- [← Roadmap](./roadmap.md)
- [Foundation →](./foundation.md)
- [Data Layer →](./data-layer.md)
- [Components →](./components.md)
- [Admin Panel →](./admin-panel.md)
- [Checklist →](./checklist.md)