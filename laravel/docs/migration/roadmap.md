# Roadmap миграции
**Extra Fitness — Yii2 → Laravel 12**

Последнее обновление: **15 июня 2026**

---

## Прогресс

| Фаза | Название | Статус | Коммиты |
|------|----------|--------|---------|
| **Phase 1** | Frontend Foundation (Tailwind, Components, Alpine) | ✅ Готово | `55b3ffa`, `edb5422` |
| **Phase 2** | Homepage + Core Backend | 🔄 В работе | `40db57e`, `5ca4fc7` |
| **Phase 3** | Аутентификация (Breeze) | ⏳ Запланировано | — |
| **Phase 4** | Статические страницы + Формы | 🔄 Каркас готов | `5ca4fc7` |
| **Phase 5** | Feature Modules (Services, News, Shares, Trainers…) | 🔄 Каркас готов | `5ca4fc7` |
| **Phase 6a** | Формы обратной связи (Callback, Contact) | ⏳ Запланировано | — |
| **Phase 6b** | Админка (Filament v3) | ⏳ Запланировано | — |
| **Phase 7** | Media Library (Spatie) | ⏳ Запланировано | — |
| **Phase 8** | SEO + Performance | ⏳ Запланировано | — |
| **Phase 9** | Strangler Fig → Продакшн | ⏳ Запланировано | — |

---

## Strangler Fig — план переключения маршрутов

| Маршрут | Цель | Фаза |
|---------|------|------|
| `/` | Laravel | Phase 2 |
| `/services/*` | Laravel | Phase 5 |
| `/es/news/*` | Laravel | Phase 5 |
| `/card/shares/*` | Laravel | Phase 5 |
| `/es/command/*` | Laravel | Phase 5 |
| `/es/events/*` | Laravel | Phase 5 |
| `/es/job/*` | Laravel | Phase 5 |
| `/card/type` | Laravel | Phase 5 |
| `/es/club` | Laravel | Phase 4 |
| `/admin/*` | Laravel (Filament) | Phase 6b |
| `/*` | Yii2 (legacy) | до завершения |

---

## Текущий фокус (июнь 2026)

1. **Завершить Homepage** — секции Actions, Subscribe, Contacts → сборка `home.blade.php`
2. **Слой данных** — подключить Eloquent-модели + DTOs + Services для всех внутренних страниц
3. После этого → Phase 3 (Auth) или Phase 6b (Filament Admin)

---

## Навигация

- [phases.md](./phases.md) — детальные exit criteria
- [data-layer.md](./data-layer.md) — DTO/Service паттерны
- [checklist.md](./checklist.md) — прогресс-трекер
