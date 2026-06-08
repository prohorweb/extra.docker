# Quick Start — 5 минут до запуска

---

## Запуск проекта

```bash
# 1. Установка зависимостей
composer install
npm install

# 2. Запуск Vite для Hot Module Replacement
npm run dev

# 3. Запуск Laravel (в отдельном терминале)
php artisan serve

# 4. Админка Filament
php artisan filament:serve
```

---

## Ключевые папки

| Папка | Назначение |
|-------|------------|
| `resources/views/components/ui/` | UI примитивы (button, card, modal) |
| `resources/views/components/sections/` | Крупные секции (hero, grid, cta) |
| `resources/views/components/features/` | Доменные компоненты (services, news) |
| `resources/views/components/modals/` | Модальные окна |
| `resources/views/components/forms/` | Формы обратной связи |
| `resources/views/components/layouts/` | Layout компоненты |
| `resources/views/components/seo/` | SEO компоненты |
| `resources/views/pages/` | Страницы (home, services, news) |
| `app/Data/` | DTO классы |
| `app/Services/` | Сервисный слой |
| `app/Filament/Resources/` | Ресурсы админки Filament |
| `resources/css/` | CSS с Design Tokens `@theme` |
| `resources/js/alpine/` | Alpine.js stores и компоненты |

---

## Быстрая навигация

| Задача | Где смотреть |
|--------|-------------|
| Создать новый Feature Module | [patterns.md](./patterns.md) |
| Добавить SEO для страницы | [seo.md](./seo.md) |
| Добавить форму (Callback/Contact) | [cookbook.md](./cookbook.md) |
| Настроить Filament ресурс | [admin-panel.md](./admin-panel.md) |
| Разобраться с ошибками | [troubleshooting.md](./troubleshooting.md) |

---

## Команды для разработки

| Команда | Описание |
|---------|----------|
| `npm run dev` | Запуск Vite Dev Server с HMR |
| `npm run build` | Production сборка ассетов |
| `php artisan queue:work` | Запуск очереди (для email уведомлений) |
| `php artisan make:filament-resource` | Создать новый Filament ресурс |
| `php artisan make:component` | Создать новый Blade компонент |
| `php artisan make:data` | Создать новый DTO класс |
| `php artisan test` | Запустить тесты |

---

## Навигация

- [← README](./README.md)
- [Roadmap →](./roadmap.md)
- [Patterns →](./patterns.md)
- [Cookbook →](./cookbook.md)