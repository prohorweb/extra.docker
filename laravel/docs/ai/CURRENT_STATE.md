# Текущее состояние — Миграция Yii2 → Laravel

## Обновлено: 16 июня 2026

## Статус миграции
**Проект**: Extra Fitness — Yii2 → Laravel 12 (Strangler Fig Pattern)
**Активная фаза**: Phase 2 — Завершение главной страницы

---

## Выполнено

### Инфраструктура ✅
- [x] Docker: PHP 8.3, Nginx, MySQL, Node/Vite
- [x] Два хоста: `extra.loc` (Yii2) + `extra.new` (Laravel 12)
- [x] AI-документация в `laravel/docs/ai/`

### Phase 1 — Фронтенд-фундамент ✅
- [x] Tailwind CSS v4 с `@theme` design tokens
- [x] Blade-компоненты: `x-ui.*`, `x-sections.*`, `x-layout.*`
- [x] Alpine.js stores: navigation, modals
- [x] Шрифты Oswald + Roboto (self-hosted, variable)
- [x] Компонент Map (Яндекс.Карты API 2.1)

### Phase 2 — Главная страница (частично) 🔄
- [x] `HomeController@index`
- [x] `HeroDTO`
- [x] `x-sections.hero` Blade-компонент
- [ ] Остальные секции (Actions, Subscribe, Contacts)

### Phase 4/5 — Маршруты и шаблоны ✅
- [x] Все контроллеры созданы (Home, Club, Trainer, News, Event, Job, Share, Service, CardType)
- [x] Все маршруты зарегистрированы в `routes/web.php`
- [x] Все Blade-шаблоны созданы (`pages/*/index.blade.php`, `show.blade.php`)

### Слой данных — DB + Filament + Controllers ✅ (16 июня)
- [x] Миграции: taxonomies, posts, post_term, seo, settings, job_queue
- [x] Модели: Post (scopes + relations), Taxonomy, Seo, Setting
- [x] Middleware ResolveClub (subdomain → current_club)
- [x] Filament v3: AdminPanelProvider, ClubResource (4 вкладки, 4 RelationManagers)
- [x] Filament: PostResource (conditional fields, 7 nav items), TaxonomyResource, SettingsPage, SeoRelationManager
- [x] PostService: все методы для всех разделов
- [x] Все контроллеры переписаны на Post/Taxonomy/Setting модели
- [x] ClubComposer обновлён на новые модели

---

## В работе

### Главная страница (Phase 2) — остаток

| Секция | Компонент | Статус |
|--------|-----------|--------|
| Hero (баннер/слайдер) | `x-sections.hero` | ✅ |
| Акции | `x-sections.actions` | ⏳ |
| Подписка | `x-sections.subscribe` | ⏳ |
| Контакты | `x-sections.contacts` | ⏳ |
| Сборка `home.blade.php` | — | ⏳ |

---

## Блокеры

- Нет.

---

## Следующие шаги (план по дням)

### Следующая сессия — Завершение главной страницы
1. `x-sections.actions` — сетка карточек акций (данные из `PostService::getShares()`)
2. `x-sections.subscribe` — статичный блок (без логики)
3. `x-sections.contacts` — карта + контакты из `settingPost` клуба
4. Сборка `home.blade.php` из готовых компонентов

### Потом — Контент-наполнение и SEO
- Настройки сайта через SettingsPage
- SEO для основных страниц
- Формы обратной связи (FormRequest → Mail)

---

## Статус доменов

| Домен | Фаза | Статус | Приоритет |
|-------|------|--------|-----------|
| Map | 1 | ✅ Готово | — |
| Home | 2 | 🔄 В работе | ВЫСОКИЙ |
| Static pages (каркас) | 4/5 | ✅ Каркас готов | — |
| Данные для страниц | 5 | ✅ PostService + все контроллеры | — |
| Auth (Breeze) | 3 | ⏳ Запланировано | СРЕДНИЙ |
| Формы обратной связи | 6a | ⏳ Запланировано | НИЗКИЙ |
| Админка (Filament) | 6b | ✅ Filament v3 настроен | — |
| Media Library | 7 | ⏳ Запланировано | НИЗКИЙ |
| SEO + Performance | 8 | ⏳ Запланировано | НИЗКИЙ |
| Strangler Fig → Прод | 9 | ⏳ Запланировано | НИЗКИЙ |
