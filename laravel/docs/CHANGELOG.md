# Changelog — Extra Fitness Migration
**Yii2 → Laravel 12 (Strangler Fig)**

---

## [Unreleased] — Phase 2: Данные для страниц 🔄

### В работе
- Главная страница: завершение секций (Actions, Subscribe, Contacts)
- Слой данных для внутренних страниц: Eloquent-модели → DTOs → Services

---

## [0.4.0] — 2026-06-15 — Каркас всех страниц

### Добавлено
- Контроллеры для всех разделов: `ClubController`, `TrainerController`, `NewsController`, `EventController`, `JobController`, `ShareController`, `ServiceController`, `CardTypeController`
- Маршруты в `routes/web.php` — все разделы с именованными именами
- Blade-шаблоны для всех страниц: index + show где применимо
  - `pages/club/`, `pages/trainers/`, `pages/news/`, `pages/events/`
  - `pages/jobs/`, `pages/shares/`, `pages/services/`, `pages/card/`

### Архитектура
- Контроллеры тонкие, готовы к подключению сервисного слоя
- Маршруты сгруппированы по смысловым префиксам (`/es/`, `/card/`, `/services`)

---

## [0.3.0] — 2026-06-12 — Homepage Foundation

### Added
- `HomeController@index` — thin controller, delegates to `HomepageService`
- `HeroDTO` — immutable data object for hero section (separates DB from view)
- Blade component `x-sections.hero` — video + banner slider section
- Connected legacy Yii2 CSS styles to new Laravel layout via `app.css`

### Architecture
- Established DTO pattern for homepage data flow: `Controller → DTO → Blade`
- Consistent with Map component approach (ADR-006)

---

## [0.2.0] — 2026-06-08 to 2026-06-11 — Frontend Foundation ✅

### Added
- Inner pages layout and structure
- Home page layout with scroll slider
- Controllers, views, and routes scaffolding (Laravel side)
- Blade component architecture: `x-ui.*`, `x-sections.*`, `x-layout.*`
- Alpine.js stores: `navigation`, `modals`
- Tailwind CSS v4 with full `@theme` design tokens (colors, typography, spacing, shadows)
- Fonts: Oswald + Roboto (variable + all static weights, self-hosted)
- Map component — fully migrated to Laravel + Tailwind v4 + Yandex Maps API 2.1
- Domain specs: `docs/ai/domains/home.md`, `docs/ai/domains/map.md`

### Infrastructure
- Consolidated AI documentation from two `ai/` folders into `laravel/docs/ai/`
- Standardized all core migration documentation in English
- Memory Bank + Cline commit rules configured

---

## [0.1.0] — 2026-05-XX to 2026-06-07 — Infrastructure ✅

### Added
- Docker Compose setup: PHP 8.3, Nginx, MySQL, Node (Vite HMR)
- Laravel 12 application bootstrapped inside Docker
- Dual-host Nginx configuration:
  - `extra.loc` → Yii2 legacy
  - `extra.new` → Laravel 12 target
- HTTPS configuration for local development
- Node container + Vite HMR
- AI operational layer: `docs/ai/` with `SYSTEM.md`, `PROTOCOL.md`, `RUNTIME.md`, `MIGRATION_RULES.md`, `DECISION_LOG.md`
- Architecture Decision Records (ADR-001 through ADR-006)

---

## Legend

| Symbol | Meaning |
|--------|---------|
| ✅ | Phase complete |
| 🔄 | In progress |
| ⏳ | Planned |
