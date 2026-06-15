# Документация по миграции
**Yii2 → Laravel 12** — Extra Fitness

Последнее обновление: **15 июня 2026**

---

## Технологический стек

| Слой | Технологии |
|------|------------|
| **Frontend** | Tailwind CSS v4 + Blade Components + Alpine.js |
| **Backend** | Laravel 12 + Eloquent + DTO + Services + FormRequests |
| **Admin Panel** | FilamentPHP v3 |
| **Notifications** | Laravel Mail + Queue (Redis/Database) |
| **Media** | Spatie Laravel Media Library |
| **Testing** | Pest PHP + Laravel Dusk (E2E) |
| **Deployment** | Docker + Nginx (Strangler Fig) |

---

## Архитектурные принципы

| Принцип | Описание |
|---------|----------|
| **Vertical Slice** | Мигрируем полноценные фичи (Home → Services → News…), не слои |
| **Strangler Fig** | Laravel параллельно Yii2, Nginx переключает маршруты поочерёдно |
| **Design System First** | Tailwind v4 `@theme` — единственный источник дизайн-токенов |
| **DTO-first** | Blade получает только `readonly class` Data-объекты, никогда сырые Eloquent-модели |
| **Zero Legacy** | Нет Bootstrap, нет jQuery, нет `@apply` цепочек длиннее 3 |
| **SEO-first** | Каждая публичная страница имеет SEOData, JSON-LD, OG-теги |

---

## Важные ограничения

| Ограничение | Статус |
|-------------|--------|
| ❌ **Подписка на рассылку** | Не реализуется |
| ✅ **Формы обратной связи** | Callback + Contact |
| ✅ **Админка** | Только FilamentPHP v3 |
| ✅ **Аутентификация** | Laravel Breeze (Blade + Alpine) |

---

## Навигация по документации

### Стратегия
- [roadmap.md](./roadmap.md) — фазы и прогресс
- [phases.md](./phases.md) — детальные exit criteria для каждой фазы
- [adr.md](./adr.md) — архитектурные решения (ADR)

### Архитектура
- [data-layer.md](./data-layer.md) — DTO / Service / Repository паттерны с примерами кода
- [components.md](./components.md) — таксономия Blade-компонентов
- [foundation.md](./foundation.md) — Design System, Tailwind токены, Alpine stores
- [layout.md](./layout.md) — структура `resources/views/`

### Стандарты
- [rules.md](./rules.md) — Coding Rules & Definition of Done
- [patterns.md](./patterns.md) — шаблоны кода для генерации модулей
- [cookbook.md](./cookbook.md) — готовые рецепты (Callback, Contact, Mail, Queue)
- [prompts.md](./prompts.md) — AI-промпты для Continue / Claude

### Операции
- [testing.md](./testing.md) — стратегия тестирования (Pest, Feature, E2E)
- [seo.md](./seo.md) — SEOData, JSON-LD, Sitemap
- [admin-panel.md](./admin-panel.md) — Filament v3: ресурсы, виджеты, права
- [deployment.md](./deployment.md) — Strangler Fig deployment, CI/CD, rollback

### Справочники
- [checklist.md](./checklist.md) — детальный трекер прогресса по фазам
- [quickstart.md](./quickstart.md) — запуск проекта за 5 минут
- [troubleshooting.md](./troubleshooting.md) — частые проблемы и решения
