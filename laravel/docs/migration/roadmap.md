# Migration Roadmap
**Extra Fitness** — Yii2 → Laravel 11 (Full Stack)

---

## Версионирование
| Поле | Значение |
|------|----------|
| **Версия** | 1.0.0 |
| **Последнее обновление** | 2025-01-XX |
| **Статус** | Active |
| **Ответственный** | @lead-dev |

---

## Прогресс миграции

| Фаза | Название | Статус | Зависимости | PR / Коммит |
|------|----------|--------|-------------|-------------|
| **Phase 1** | Foundation (Frontend) | ✅ Done | — | `#1` |
| **Phase 2** | Homepage + Core Backend | 🔄 In Progress | Phase 1 | `#2` |
| **Phase 3** | Authentication (Breeze) | ⏳ Planned | Phase 2 | — |
| **Phase 4** | Static Pages + Contact Forms | ⏳ Planned | Phase 2 | — |
| **Phase 5** | Feature Modules (Public) | ⏳ Planned | Phase 2 | — |
| **Phase 6a** | Public Forms (Callback, Contact) | ⏳ Planned | Phase 4 | — |
| **Phase 6b** | Admin Panel (Filament v3) | ⏳ Planned | Phase 6a | — |
| **Phase 7** | Media + File Uploads | ⏳ Planned | Phase 5 | — |
| **Phase 8** | SEO + Performance | ⏳ Planned | Phase 5 | — |
| **Phase 9** | Strangler Fig + Production | ⏳ Planned | All | — |

---

## Ключевые принципы

| Принцип | Реализация |
|---------|------------|
| **Vertical Slice Migration** | Каждая фаза = полноценная фича: Controller + DTO + Service + Blade + SEO + Tests |
| **Strangler Fig** | Nginx маршрутизирует `/` → Laravel, остальное → Yii2; по фазам переключаем |
| **Design System First** | Tailwind v4 `@theme` в `resources/css/app.css` — Single Source of Truth |
| **DTO-first** | Blade получает только `Data` классы из `app/Data/` |
| **Zero Legacy** | Нет Bootstrap, jQuery, `@apply` > 3 строк |
| **Filament v3 Only** | Админка исключительно на Filament |
| **Mail + Queue** | Все уведомления через Laravel Mail + Redis/Database queue |
| **No Subscribe** | Подписка на рассылку **не реализуется** |

---

## Формы обратной связи (в scope)

| Форма | Поля | Backend | Admin |
|-------|------|---------|-------|
| **Callback** (обратный звонок) | name, phone, club_id, consent, honeypot | `CallbackController` + `CallbackRequest` + `CallbackNotification` (Mail) | `CallbackResource` (view, export) |
| **Contact** (контактная форма) | name, email, phone, subject, message, consent, honeypot | `ContactController` + `ContactRequest` + `ContactNotification` (Mail) | `ContactResource` (view, reply, export) |

---

## Навигация

- [← README](./README.md)
- [Phases Detail →](./phases.md)
- [Foundation →](./foundation.md)
- [Data Layer →](./data-layer.md)
- [Components →](./components.md)
- [Admin Panel →](./admin-panel.md)
- [Deployment →](./deployment.md)
- [Checklist →](./checklist.md)