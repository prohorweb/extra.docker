# Migration Documentation
**Yii2 → Laravel 12 (Full Stack)** — Extra Fitness

Центральная документация по миграции фронтенда и бэкенда.

---

## Версионирование
| Поле | Значение |
|------|----------|
| **Версия** | 1.0.① |
| **Последнее обновление** | 2025-01-XX |
| **Статус** | Active |
| **Ответственный** | @lead-dev |

---

## Технологический стек

| Слой | Технологии |
|------|------------|
| **Frontend** | Tailwind CSS v4 + Blade Components + Alpine.js |
| **Backend** | Laravel 12 + Eloquent + DTO + Services + Form Requests |
| **Admin Panel** | FilamentPHP v3 |
| **Notifications** | Laravel Mail + Queue (Redis/Database) |
| **Media** | Spatie Laravel Media Library |
| **Testing** | Pest PHP + Laravel Dusk (E2E) |
| **CI/CD** | GitHub Actions |
| **Deployment** | Docker + Nginx (Strangler Fig) |

---

## Архитектурные принципы

| Принцип | Описание |
|---------|----------|
| **Vertical Slice Migration** | Мигрируем полноценные фичи (Home → Services → News…), не слои |
| **Strangler Fig** | Laravel запускается параллельно Yii2, Nginx переключает маршруты по одному |
| **Design System First** | Tailwind v4 `@theme` — единственный источник дизайн-токенов |
| **DTO-first** | Blade получает только Data-классы, никогда сырые Eloquent модели |
| **Zero Legacy** | Нет Bootstrap, нет jQuery, нет `@apply` цепочек длиннее 3 строк |
| **SEO-first** | Каждая публичная страница имеет SEOData, JSON-LD, OG теги |

---

## Быстрый старт

| Задача | Документ |
|--------|----------|
| Запуск за 5 минут | [quickstart.md](./quickstart.md) |
| Общий план миграции | [roadmap.md](./roadmap.md) |
| Детали фаз | [phases.md](./phases.md) |
| Создание новой фичи | [patterns.md](./patterns.md) |
| Промпты для AI | [prompts.md](./prompts.md) |
| Решение проблем | [troubleshooting.md](./troubleshooting.md) |

---

## Навигация по документации

### Foundation
- [UI Foundation & Design System](./foundation.md) — токены, компоненты, Alpine
- [Data Layer](./data-layer.md) — Models → DTO → Services → Controllers
- [Component Taxonomy](./components.md) — ui/sections/features/modals/layouts

### Implementation
- [Migration Phases](./phases.md) — Phase 1–10 с exit criteria
- [SEO Infrastructure](./seo.md) — SEOData, JSON-LD, Sitemap
- [Admin Panel (Filament v3)](./admin-panel.md) — ресурсы, виджеты, права
- [Forms & Notifications](./cookbook.md) — Callback, Contact, Mail, Queue

### Operations
- [Deployment & Strangler Fig](./deployment.md) — Nginx, CI/CD, env, rollback
- [Coding Rules & DoD](./rules.md) — стандарты, Git workflow, PR template
- [Architecture Decision Records](./adr.md) — ADR-001…005
- [Testing Strategy](./testing.md) — Pest, Feature, Component, E2E

### Reference
- [Migration Checklist](./checklist.md) — детальный трекер прогресса
- [Cookbook](./cookbook.md) — готовые рецепты кода
- [Troubleshooting](./troubleshooting.md) — частые проблемы и решения
- [AI Prompts](./prompts.md) — промпты для Continue/Qwen/Codex
- [Code Generation Patterns](./patterns.md) — шаблоны для генерации модулей

---

## Важные ограничения

| Ограничение | Статус |
|-------------|--------|
| ❌ **Подписка на рассылку (Subscribe)** | **Не будет реализована** |
| ✅ **Формы обратной связи** | Callback (обратный звонок) + Contact (контактная форма) |
| ✅ **Админка** | Только FilamentPHP v3 |
| ✅ **Миграция** | Vertical Slice + Strangler Fig |

---

## Полезные ссылки

- [Laravel 12 Documentation](https://laravel.com/docs/12.x)
- [Tailwind CSS v4](https://tailwindcss.com/docs)
- [Alpine.js](https://alpinejs.dev/)
- [FilamentPHP v3](https://filamentphp.com/docs/3.x)
- [Spatie Media Library](https://spatie.be/docs/laravel-medialibrary)
- [Pest PHP](https://pestphp.com/)