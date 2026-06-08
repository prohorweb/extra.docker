# Migration Checklist — Detailed Progress Tracker

---

## Phase 1 — Foundation (Frontend) ✅

### Design Tokens
- [x] Colors: brand (500-950), surface, text, semantic tokens
- [x] Typography: heading (Oswald), body (Roboto), scale (display-xl to text-xs)
- [x] Spacing: 18, 22, 30
- [x] Radius: card (1.5rem), section (2rem), full (9999px)
- [x] Shadows: card, elevated, glow-brand
- [x] Transitions: fast (150ms), base (200ms), slow (300ms)
- [x] Z-index scale: dropdown (100), sticky (200), modal-backdrop (300), modal (400), toast (500)

### UI Primitives (`x-ui.*`)
- [x] `x-ui.button` — brand, outline, ghost variants; sm, md, lg sizes
- [x] `x-ui.card` — base, interactive with hover effects
- [x] `x-ui.input` + `x-ui.label` — form controls with validation states
- [x] `x-ui.badge` — brand, success, warning variants
- [x] `x-ui.modal` — portal-based, focus trap, ESC close, ARIA attributes
- [ ] `x-ui.pagination` — pagination component
- [ ] `x-ui.avatar` — user/trainer avatar
- [ ] `x-ui.dropdown` — dropdown menu

### Section Components (`x-sections.*`)
- [x] `x-sections.hero` — video background, heading, CTA, scroll indicator
- [ ] `x-sections.grid` — responsive grid with column config
- [ ] `x-sections.cta` — call to action section
- [ ] `x-sections.slider` — Swiper integration
- [ ] `x-sections.stats` — statistics display
- [ ] `x-sections.testimonials` — testimonials/testimonials

### Layout Components
- [x] `x-layout` — main layout with header, main, footer
- [ ] `x-layout-guest` — layout for auth pages
- [ ] `x-layout-empty` — minimal layout (landing, maintenance)
- [x] `x-header` — sticky header with navigation, mobile menu, optional elements
- [x] `x-footer` — footer with links, contacts, copyright
- [x] `x-navigation` — main navigation with dropdowns

### Alpine.js Architecture
- [x] `navigation` store — mobile menu, dropdowns, scroll state
- [x] `modals` store — open/close modals, body scroll lock
- [ ] `theme` store — dark/light mode
- [ ] `video-background` component — IntersectionObserver-based lazy loading
- [ ] `form-handler` component — AJAX form submission, validation, CSRF

### Responsive
- [x] Desktop (1280px+) — full navigation, multi-column grids
- [x] Tablet (768px-1279px) — condensed navigation, 2-column grids
- [x] Mobile (<768px) — hamburger menu, single column

### Accessibility
- [x] `:focus-visible` styles on all interactive elements
- [x] ARIA labels on buttons, navigation, modals
- [x] Semantic HTML: `<header>`, `<nav>`, `<main>`, `<footer>`, `<article>`
- [ ] Keyboard navigation: Tab, Enter, Escape
- [ ] Screen reader testing

---

## Phase 2 — Homepage + Core Backend 🔄 IN PROGRESS

### Eloquent Models
- [ ] `Club` — migration, factory, seeder with sample data
- [ ] `Setting` — migration, factory, seeder with key-value defaults
- [ ] `Service` — migration, factory, seeder
- [ ] `Share` — migration, factory, seeder
- [ ] `Trainer` — migration, factory, seeder
- [ ] `Banner` — migration, factory, seeder
- [ ] `Metro` — migration, factory, seeder
- [ ] Relations: `Service` belongsTo `Category`, `Trainer` hasMany `Specialization`, etc.

### Data Layer
- [ ] `HomepageData` — aggregate all homepage DTOs
- [ ] `HeroData` — hero section data (video, poster, heading, subheading)
- [ ] `ShareCardData` — share card with image, title, dates, badge
- [ ] `ServiceCardData` — service card with image, title, price, tags
- [ ] `TrainerCardData` — trainer card with photo, name, specialization, rating
- [ ] `SEOData` — SEO metadata (title, description, canonical, OG, JSON-LD)

### Service Layer
- [ ] `HomepageService` — aggregates all homepage data
  - `getHero()` → `HeroData`
  - `getFeaturedShares()` → `ShareCardData[]`
  - `getFeaturedServices()` → `ServiceCardData[]`
  - `getFeaturedTrainers()` → `TrainerCardData[]`
  - `getMetroStations()` → `Metro[]`
  - `getClubInfo()` → `Club`

### Controller & Routes
- [ ] `HomeController@index` — returns view with `HomepageData`
- [ ] Route `/` → `HomeController@index`
- [ ] Route name: `home`

### Blade
- [ ] `pages/home.blade.php` — full homepage with all sections
- [ ] All section components integrated
- [ ] SEOData passed to layout

### Tests
- [ ] Feature test: homepage returns 200
- [ ] Feature test: homepage loads all sections
- [ ] Unit test: `HomepageData::fromRequest()`
- [ ] Unit test: each DTO `fromModel()` method

---

## Phase 3 — Authentication ⏳

### Laravel Breeze
- [ ] `composer require laravel/breeze --dev`
- [ ] `php artisan breeze:install blade`
- [ ] Auth views customized with Design System
- [ ] Email verification configured
- [ ] Password reset configured
- [ ] `User` model: roles, profile

### UI
- [ ] Login page: `x-layout-guest`, `x-ui.input`, `x-ui.button`
- [ ] Register page: fields, validation, honeypot
- [ ] Password reset: request + reset forms
- [ ] Email verification: notice + resend

### Tests
- [ ] Feature test: successful registration
- [ ] Feature test: invalid login
- [ ] Feature test: password reset flow

---

## Phase 4 — Static Pages + Contact Forms ⏳

### Static Pages
- [ ] `about` — Company story, team values
- [ ] `contacts` — Map, phone, email, form
- [ ] `club` — Club overview, facilities, metro stations
- [ ] `legal` — Legal documents
- [ ] `privacy` — Privacy policy
- [ ] `offer` — Public offer

### Form Backend
- [ ] `Callback` model + migration
- [ ] `Contact` model + migration
- [ ] `CallbackRequest` — validation rules
- [ ] `ContactRequest` — validation rules
- [ ] `CallbackNotification` — Mailable + Queue
- [ ] `ContactNotification` — Mailable + Queue
- [ ] `CallbackController@store` — JSON response
- [ ] `ContactController@store` — JSON response
- [ ] Rate limiting: `throttle:3,1`
- [ ] Honeypot field validation

### Alpine Components
- [ ] `x-forms.callback` — phone, name, club select, consent, honeypot
- [ ] `x-forms.contact` — name, email, phone, subject, message, consent, honeypot

### Tests
- [ ] Feature test: Callback form submission
- [ ] Feature test: Contact form submission
- [ ] Feature test: rate limiting returns 429
- [ ] Feature test: validation errors

---

## Phase 5 — Feature Modules (Public) ⏳

### Per Module Checklist (use [Patterns](./patterns.md))

- [ ] Eloquent Model + Migration + Factory + Seeder
- [ ] Data Classes: `*CardData`, `*DetailData`, `*FilterData`
- [ ] Service Class: `getFeatured()`, `getPaginated()`, `getBySlug()`
- [ ] Controller: `index`, `show`
- [ ] Form Request: filter validation
- [ ] Blade Components: `x-features.{module}.card`, `grid`, `detail`
- [ ] Pages: `index.blade.php`, `show.blade.php`
- [ ] SEOData + JSON-LD for both pages
- [ ] Filament Resource
- [ ] Feature Tests

### Specific Modules
- [ ] **Services** — priority: P0
- [ ] **News** — priority: P0 (news section active)
- [ ] **Shares** — priority: P1
- [ ] **Trainers** — priority: P0 (main page section)
- [ ] **Events** — priority: P2
- [ ] **Programs** — priority: P1
- [ ] **Jobs** — priority: P2
- [ ] **Articles** — priority: P3

---

## Phase 6a — Public Forms (Callback, Contact) ⏳

- [ ] Callback model + migration (name, phone, club_id, consent, processed_at, honeypot)
- [ ] Contact model + migration (name, email, phone, subject, message, consent, processed_at, honeypot)
- [ ] `CallbackRequest` validation
- [ ] `ContactRequest` validation
- [ ] Mail + Queue configured
- [ ] Rate limiting working
- [ ] Honeypot protection
- [ ] Alpine form components
- [ ] Tests: validation, success, rate limit

---

## Phase 6b — Admin Panel (Filament v3) ⏳

### Setup
- [ ] Filament v3 installed
- [ ] Admin panel configured (branding, colors, fonts)
- [ ] Dark mode enabled

### Resources
- [ ] `CallbackResource` — view, export CSV, status toggle
- [ ] `ContactResource` — view, reply via mail, export
- [ ] `ServiceResource` — CRUD, Media Library, Rich Editor
- [ ] `ShareResource` — CRUD, Media Library, Date Range
- [ ] `NewsResource` — CRUD, Categories, Scheduling
- [ ] `TrainerResource` — CRUD, Media Library, Specializations
- [ ] `EventResource` — CRUD, Media Library
- [ ] `ProgramResource` — CRUD, Media Library
- [ ] `JobResource` — CRUD, Application Management
- [ ] `ArticleResource` — CRUD, Categories
- [ ] `ClubResource` — CRUD, Media Library
- [ ] `SettingResource` — Key-Value Editor
- [ ] `UserResource` — CRUD, Roles, Permissions

### Widgets
- [ ] `StatsOverview` — callbacks, contacts, services, trainers
- [ ] `LatestCallbacks` — table of recent form submissions
- [ ] Chart widgets for trends

### Permissions
- [ ] Roles: admin, editor, viewer
- [ ] Permission middleware in `AdminPanelProvider`
- [ ] Filament Shield installed

---

## Phase 7 — Media + File Uploads ⏳

- [ ] Spatie Media Library installed
- [ ] `media` disk configured (local/S3)
- [ ] Image conversions: thumb (400x300), preview (800x600), og (1200x630)
- [ ] Responsive images via `srcset`
- [ ] Video: poster + lazy loading
- [ ] All models with media: `HasMedia` trait
- [ ] Upload directory structure
- [ ] Cleanup on model delete

---

## Phase 8 — SEO + Performance ⏳

### SEO
- [ ] `SEOData` DTO in all public pages
- [ ] `x-seo.meta` component in layout
- [ ] JSON-LD: Organization, Service, Article, Event, Person, BreadcrumbList
- [ ] Canonical URLs
- [ ] OG Tags + Twitter Cards
- [ ] Sitemap auto-generation
- [ ] Robots.txt

### Performance
- [ ] Lighthouse: Performance > 90
- [ ] Lighthouse: Accessibility > 95
- [ ] Lighthouse: SEO > 95
- [ ] Fonts: self-hosted variable, preloaded
- [ ] Images: WebP/AVIF, lazy loading, responsive
- [ ] JS: code-splitting, lazy imports
- [ ] CI: Lighthouse CI in GitHub Actions

---

## Phase 9 — Strangler Fig + Production ⏳

### Nginx
- [ ] Initial config: all routes → Yii2 except `/`
- [ ] Phase routing: `/services/*` → Laravel
- [ ] Phase routing: `/news/*` → Laravel
- [ ] Phase routing: `/shares/*` → Laravel
- [ ] Phase routing: remaining modules → Laravel
- [ ] Final config: all routes → Laravel

### DevOps
- [ ] Zero-downtime deployment
- [ ] Docker health checks
- [ ] Monitoring: Sentry, Telescope
- [ ] Backup: DB, Media, Env
- [ ] Rollback procedure
- [ ] Load testing: 1000 RPS

### Security
- [ ] CSP headers
- [ ] HSTS
- [ ] Rate limiting on all forms
- [ ] Input validation (Form Requests)
- [ ] SQL injection prevention

---

## Navigation

- [← Roadmap](./roadmap.md)
- [Phases Detail →](./phases.md)
- [Testing Strategy →](./testing.md)
- [Troubleshooting →](./troubleshooting.md)