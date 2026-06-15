# Текущее состояние — Миграция Yii2 → Laravel

## Обновлено: 15 июня 2026

## Статус миграции
**Проект**: Extra Fitness — Yii2 → Laravel 12 (Strangler Fig Pattern)
**Активная фаза**: Phase 2/4/5 — Подключение данных

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

---

## В работе

**Слой данных** — подключение Eloquent-моделей → DTO → Service для каждого раздела

| Раздел | Модель | DTO | Service | Статус |
|--------|--------|-----|---------|--------|
| Главная | Club, Share, Banner, Metro | HomepageData, HeroDTO | HomepageService | 🔄 Частично |
| Услуги | Service | ServiceCardData, ServiceDetailData | ServiceService | ⏳ |
| Новости | News | NewsCardData, NewsDetailData | NewsService | ⏳ |
| Акции | Share | ShareCardData, ShareDetailData | ShareService | ⏳ |
| Тренеры | Trainer | TrainerCardData, TrainerDetailData | TrainerService | ⏳ |
| События | Event | EventCardData | EventService | ⏳ |
| Вакансии | Job | JobCardData | JobService | ⏳ |
| Клуб | Club, Metro | ClubData | ClubService | ⏳ |
| Типы карт | CardType | CardTypeData | — | ⏳ |

---

## Блокеры

- Нет.

---

## Следующие шаги (план по дням)

### 16 июня — Главная страница (завершение Phase 2)
1. `x-sections.actions` + `ShareCardData` DTO
2. `x-sections.subscribe`
3. `x-sections.contacts` (карта + Metro)
4. Сборка `home.blade.php` из компонентов
5. `HomepageService` + `HomepageData` (агрегация данных)

### 17 июня — Слой данных: Услуги + Новости
1. Eloquent-модели `Service`, `News` (проверить/создать)
2. Миграции + seeders
3. DTOs: `ServiceCardData`, `ServiceDetailData`, `NewsCardData`, `NewsDetailData`
4. `ServiceService`, `NewsService`
5. Подключить данные в `ServiceController`, `NewsController`

### 18 июня — Слой данных: Акции + Тренеры
1. Модели `Share`, `Trainer`
2. DTOs + Services для обоих разделов
3. Подключить в контроллеры

### 19 июня — Слой данных: Events + Jobs + Club
1. Модели, DTOs, Services для оставшихся разделов
2. Подключить в контроллеры

---

## Статус доменов

| Домен | Фаза | Статус | Приоритет |
|-------|------|--------|-----------|
| Map | 1 | ✅ Готово | — |
| Home | 2 | 🔄 В работе | ВЫСОКИЙ |
| Static pages (каркас) | 4/5 | ✅ Каркас готов | — |
| Данные для страниц | 5 | ⏳ Следующий шаг | ВЫСОКИЙ |
| Auth (Breeze) | 3 | ⏳ Запланировано | СРЕДНИЙ |
| Формы обратной связи | 6a | ⏳ Запланировано | НИЗКИЙ |
| Админка (Filament) | 6b | ⏳ Запланировано | НИЗКИЙ |
| Media Library | 7 | ⏳ Запланировано | НИЗКИЙ |
| SEO + Performance | 8 | ⏳ Запланировано | НИЗКИЙ |
| Strangler Fig → Прод | 9 | ⏳ Запланировано | НИЗКИЙ |
