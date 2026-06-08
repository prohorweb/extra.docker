# Architecture Decision Records (ADRs)

---

## ADR-001: Strangler Fig + Vertical Slice Migration

| Property | Value |
|----------|-------|
| **Status** | Accepted |
| **Date** | 2025-01-XX |
| **Context** | Legacy Yii2 monolith requires modernisation without Big Bang |
| **Decision** | Run Laravel in parallel, intercept routes via Nginx one feature at a time |
| **Consequences** | + Zero regression risk + Parallel development - Routing complexity - Dual maintenance in transition |
| **Alternatives** | Big Bang Rewrite (rejected: high risk, no business continuity) |

---

## ADR-002: Tailwind v4 as Sole Design System

| Property | Value |
|----------|-------|
| **Status** | Accepted |
| **Date** | 2025-01-XX |
| **Context** | Legacy Bootstrap/jQuery styles cause bloat and inconsistency |
| **Decision** | Use Tailwind CSS v4 with `@theme` as Single Source of Truth for all styles |
| **Consequences** | + Design consistency + Small bundle via purging - Learning curve for team - No legacy class reuse allowed |
| **Alternatives** | Keep Bootstrap + SCSS (rejected: tech debt), CSS Modules (rejected: complexity) |

---

## ADR-003: Zero Bootstrap / Zero jQuery

| Property | Value |
|----------|-------|
| **Status** | Accepted |
| **Date** | 2025-01-XX |
| **Context** | Legacy code heavily uses Bootstrap CSS and jQuery for DOM manipulation |
| **Decision** | Completely remove Bootstrap and jQuery. Use Tailwind v4 + Alpine.js instead. |
| **Consequences** | + Modern codebase + Smaller payload + Native JS (IntersectionObserver, fetch) - Migration effort for all jQuery-dependent components |
| **Alternatives** | Keep jQuery (rejected: adds 30KB+ minified, outdated patterns) |

---

## ADR-004: DTO Pattern (Data Transfer Objects)

| Property | Value |
|----------|-------|
| **Status** | Accepted |
| **Date** | 2025-01-XX |
| **Context** | Blade templates need typed, immutable data without Eloquent complexity |
| **Decision** | All Blade views receive `readonly class` DTOs from `App\\Data\\`, never raw Eloquent models |
| **Consequences** | + Type safety + Testability + Ease of future API rendering - More files to maintain - Slight overhead for simple pages |
| **Alternatives** | Pass Eloquent models directly (rejected: N+1, security exposure, coupling) |

---

## ADR-005: FilamentPHP v3 for Admin Panel

| Property | Value |
|----------|-------|
| **Status** | Accepted |
| **Date** | 2025-01-XX |
| **Context** | Admin panel needs CRUD, media management, user roles, and analytics |
| **Decision** | Use FilamentPHP v3 as the exclusive admin panel framework |
| **Consequences** | + Rapid CRUD generation + Media Library integration + Built-in theming - Third-party dependency - Limited custom page flexibility |
| **Alternatives** | Voyager (rejected: outdated), Laravel Nova (rejected: cost), Custom panel (rejected: development time) |

---

## ADR-006: Laravel Breeze (Blade + Alpine) for Authentication

| Property | Value |
|----------|-------|
| **Status** | Accepted |
| **Date** | 2025-01-XX |
| **Context** | Login/registration system needed with email verification and password reset |
| **Decision** | Use Laravel Breeze with Blade + Alpine stack, then customise with Design System |
| **Consequences** | + Rapid setup + Tested security patterns + Customisable UI - Overhead if requirements deviate significantly |
| **Alternatives** | Fortify (rejected: headless, more work), Jetstream (rejected: adds Livewire/Inertia) |

---

## ADR-007: No Subscribe (Newsletter) Feature

| Property | Value |
|----------|-------|
| **Status** | Accepted |
| **Date** | 2025-01-XX |
| **Context** | Newsletter subscription was present in legacy Yii2 |
| **Decision** | Do NOT implement subscription functionality in Laravel. Only Callback and Contact forms. |
| **Consequences** | + Simpler codebase + No GDPR complexity for storing emails - Existing subscribers need alternative management |
| **Alternatives** | Implement minimal subscribe (rejected: not required by business) |

---

## ADR-008: Mail + Queue for Notifications

| Property | Value |
|----------|-------|
| **Status** | Accepted |
| **Date** | 2025-01-XX |
| **Context** | Form submissions (Callback, Contact) need admin notifications |
| **Decision** | Use Laravel Mail + Redis Queue for all email notifications |
| **Consequences** | + Async delivery + Retry logic + No blocking UI - Additional infrastructure (Redis, worker) |
| **Alternatives** | Sync mail (rejected: blocks HTTP response), External service API (rejected: dependency) |

---

## ADR-009: Spatie Media Library for All Media

| Property | Value |
|----------|-------|
| **Status** | Accepted |
| **Date** | 2025-01-XX |
| **Context** | Direct `/uploads/` paths in Yii2 cause portability and optimisation issues |
| **Decision** | Use Spatie Laravel Media Library with image conversions, responsive images, and CDN-ready abstraction |
| **Consequences** | + Centralised media management + Automatic conversions + CDN-ready - Model coupling to media trait |
| **Alternatives** | Direct filesystem (rejected: no conversions, no abstraction) |

---

## ADR Template

```markdown
## ADR-NNN: Short Title

| Property | Value |
|----------|-------|
| **Status** | [Proposed / Accepted / Deprecated / Superseded] |
| **Date** | YYYY-MM-DD |
| **Context** | Why this decision was needed |
| **Decision** | What was decided |
| **Consequences** | + Pros - Cons |
| **Alternatives** | What was considered and why it was rejected |
| **Links** | Related PRs, issues, documents |
```

---

## Navigation

- [← Roadmap](./roadmap.md)
- [Foundation →](./foundation.md)
- [Rules →](./rules.md)