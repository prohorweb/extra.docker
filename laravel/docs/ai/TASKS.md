# Задачи — Миграция Yii2 → Laravel

## Обновлено: 15 июня 2026

---

## ✅ Выполнено

### Инфраструктура
- [x] Docker (PHP, Nginx, MySQL, Node/Vite)
- [x] Двойной хост Nginx: `extra.loc` / `extra.new`
- [x] AI-документация `laravel/docs/ai/`

### Phase 1 — Фронтенд-фундамент
- [x] Tailwind CSS v4 + `@theme` design tokens
- [x] Blade-компоненты (`x-ui.*`, `x-sections.*`, `x-layout.*`)
- [x] Alpine.js stores (navigation, modals)
- [x] Шрифты Oswald + Roboto (self-hosted)
- [x] Компонент Map (Яндекс.Карты)

### Phase 2 — Главная (частично)
- [x] `HomeController@index`
- [x] `HeroDTO`
- [x] `x-sections.hero`

### Phase 4/5 — Каркас страниц ✅
- [x] Все контроллеры созданы (9 штук)
- [x] Все маршруты в `routes/web.php`
- [x] Все Blade-шаблоны созданы

---

## 🔄 В работе — Главная страница (завершить)

**Приоритет: ВЫСОКИЙ**

- [ ] `x-sections.actions` — карточки акций
  - [ ] `ShareCardData` DTO
  - [ ] Blade: сетка карточек
- [ ] `x-sections.subscribe` — блок подписки
- [ ] `x-sections.contacts` — контакты с картой
  - [ ] Подключить модель `Metro`
  - [ ] DTO с адресом клуба
- [ ] Сборка `pages/home.blade.php` из компонентов
- [ ] `HomepageService` — агрегация данных главной
- [ ] `HomepageData` DTO — итоговый объект для вьюхи

---

## ⏳ Следующие задачи — БД + Админка (приоритет)

### DB Блок 1 — Переименование и настройка
- [ ] Переименовать очередь: `jobs` → `jobs_queue` в миграции (конфликт с таблицей вакансий)
- [ ] Настроить `.env` для `extra_new`
- [ ] Запустить дефолтные Laravel миграции (users, cache, sessions, tokens)

### DB Блок 2 — Контент без связей
- [ ] Миграция + модель `metros` (id, name, position)
- [ ] Миграция + модель `settings` (id, email_from, email_form_*, yandex_metrica…)
- [ ] Миграция + модель `clubs` (все поля кроме устаревших соцсетей)

### DB Блок 3 — Основные модули
- [ ] Миграция + модель `services` (id, status, position, title, img, content, alias, meta_*)
- [ ] Миграция + модель `news` (id, status, position, title, date, intro, img, content, alias, meta_*)
- [ ] Миграция + модель `shares` (id, status, position, title, intro, img, content, price, alias, meta_*)
- [ ] Миграция + модель `events` (id, status, title, date, is_pay, is_open, intro, img, content, alias, meta_*)
- [ ] Миграция + модель `jobs` (id, status, position, title, content, alias, meta_*)

### DB Блок 4 — Тренеры
- [ ] Миграция + модель `trainer_specializations`
- [ ] Миграция + модель `trainers` (со связью на specializations)
- [ ] Pivot-таблица `trainer_specialization`

### DB Блок 5 — Медиа и карточки
- [ ] Миграция + модель `club_banners` (img1440, img1200, img768, video)
- [ ] Миграция + модель `cards` (club_cards)
- [ ] Установить Spatie Media Library + миграция `media`

### DB Блок 6 — Формы
- [ ] Миграция + модель `callbacks` (name, phone, club_id, consent, processed_at, honeypot)
- [ ] Миграция + модель `contacts` (name, email, phone, subject, message, consent, processed_at, honeypot)

### Filament Блок 1 — Установка
- [ ] `composer require filament/filament`
- [ ] `php artisan filament:install --panels`
- [ ] Брендинг: primary `#c8102e`, шрифт Oswald

### Filament Блок 2 — Ресурсы (по одному на модуль)
- [ ] `ClubResource` — Edit (одна запись, все поля)
- [ ] `ServiceResource` — CRUD, position drag, status, media
- [ ] `NewsResource` — CRUD, date, status, media
- [ ] `ShareResource` — CRUD, position, price, media
- [ ] `TrainerResource` — CRUD, specializations (many-to-many), media
- [ ] `EventResource` — CRUD, date, is_pay/is_open
- [ ] `JobResource` — CRUD, status
- [ ] `ClubBannerResource` — CRUD, position, video + image
- [ ] `CardResource` — CRUD, position, price
- [ ] `MetroResource` — CRUD, position
- [ ] `SettingResource` — Edit (одна запись)
- [ ] `CallbackResource` — View only + export
- [ ] `ContactResource` — View + reply + export

### Filament Блок 3 — Связать данные с контроллерами
- [ ] `HomepageService` → данные из `Club`, `ClubBanner`, `Share`, `Metro`
- [ ] `ServiceController` → `ServiceService` → `ServiceCardData` / `ServiceDetailData`
- [ ] `NewsController` → `NewsService` → `NewsCardData` / `NewsDetailData`
- [ ] `ShareController` → `ShareService` → `ShareCardData` / `ShareDetailData`
- [ ] `TrainerController` → `TrainerService` → `TrainerCardData` / `TrainerDetailData`
- [ ] `EventController` → `EventService` → `EventCardData`
- [ ] `JobController` → `JobService` → `JobCardData`
- [ ] `ClubController` → `ClubService` → `ClubData`

---

## ⏳ Следующие задачи — Слой данных (Phase 5)

**Приоритет: ВЫСОКИЙ**

### Услуги (`/services`)
- [ ] Eloquent-модель `Service` + миграция
- [ ] `ServiceCardData`, `ServiceDetailData` DTOs
- [ ] `ServiceService` с `getAll()`, `getByAlias()`
- [ ] Подключить в `ServiceController`
- [ ] Seeder с тестовыми данными

### Новости (`/es/news`)
- [ ] Eloquent-модель `News` + миграция
- [ ] `NewsCardData`, `NewsDetailData` DTOs
- [ ] `NewsService`
- [ ] Подключить в `NewsController`

### Акции (`/card/shares`)
- [ ] Eloquent-модель `Share` + миграция
- [ ] `ShareCardData`, `ShareDetailData` DTOs
- [ ] `ShareService`
- [ ] Подключить в `ShareController`

### Тренеры (`/es/command`)
- [ ] Eloquent-модель `Trainer` + миграция
- [ ] `TrainerCardData`, `TrainerDetailData` DTOs
- [ ] `TrainerService`
- [ ] Подключить в `TrainerController`

### Остальные разделы
- [ ] `Event` + `EventCardData` + `EventService` → `EventController`
- [ ] `Job` + `JobCardData` + `JobService` → `JobController`
- [ ] `Club`, `Metro` → `ClubService` → `ClubController`
- [ ] `CardType` → `CardTypeController`

---

## ⏳ Запланировано — Phase 3: Аутентификация

- [ ] Установить Laravel Breeze
- [ ] Адаптировать auth-вьюхи под Design System
- [ ] Email-верификация + сброс пароля
- [ ] `AuthService`, `UserService`
- [ ] `Domain/User/` структура (Actions, DTOs, FormRequests)
- [ ] Feature-тесты: login, register, password reset

---

## ⏳ Запланировано — Phase 6–9

- **6a** — Формы обратной связи (Callback, Contact) + Mail + Queue
- **6b** — Filament v3 admin panel
- **7** — Spatie Media Library
- **8** — SEO + Performance (Lighthouse CI)
- **9** — Strangler Fig → Продакшн

---

## Правила

- Один контекст на коммит (не смешивать миграцию + рефакторинг + форматирование)
- Обновлять `CURRENT_STATE.md` и `domains/*.md` после завершения работы
- Читать `CURRENT_STATE.md` в начале каждой сессии
- Не удалять Yii2-код пока Laravel-замена не в проде
