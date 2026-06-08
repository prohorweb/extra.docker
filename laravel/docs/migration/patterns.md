# Code Generation Patterns — шаблоны для AI-агентов

---

## Pattern 1: Новый Feature Module (Controller + Data + Service + Blade + Tests)

### Prompt для AI

```
Создай feature module "Services" по паттерну ниже.
Требования:
- Eloquent модель Service с полями: id, title, slug, short_description, description, price_from, price_to, position, is_active, is_featured, category_id
- Медиа: изображения через Spatie Media Library (card, thumb, og)
- Теги: многие-ко-многим с Tag моделью
- Категория: belongsTo Category

Сгенерируй:
1. Миграцию + Factory + Seeder
2. Модель с Media Library trait
3. DTO: ServiceCardData, ServiceDetailData, ServiceFilterData
4. ServiceService с getFeatured(), getPaginated(), getBySlug()
5. Controller: index (paginated), show (by slug)
6. Form Request: ServiceFilterRequest
7. Blade: x-features.services.card, grid, detail + pages/services/{index,show}.blade.php
8. SEOData для обеих страниц + JSON-LD Service schema
9. Filament Resource (CRUD + Media Library + Categories)
10. Feature tests: index 200, show 200, filter работает

Constraints:
- Использовать Design Tokens из foundation.md
- DTO-first, никакого Eloquent в Blade
- Alpine.store для фильтрации
- Rate limiting не нужно (GET запросы)
```

### Структура генерации

```
app/
├── Data/Services/
│   ├── ServiceCardData.php
│   ├── ServiceDetailData.php
│   └── ServiceFilterData.php
├── Services/
│   └── ServiceService.php
├── Http/
│   ├── Controllers/Web/ServiceController.php
│   └── Requests/ServiceFilterRequest.php
├── Models/Service.php (+ migration, factory, seeder)
├── Filament/Resources/ServiceResource.php
└── Providers/ServiceServiceProvider.php (если нужно)

resources/views/
├── components/features/services/
│   ├── card.blade.php
│   ├── grid.blade.php
│   └── detail.blade.php
├── pages/services/
│   ├── index.blade.php
│   └── show.blade.php

database/
├── migrations/xxxx_create_services_table.php
├── factories/ServiceFactory.php
└── seeders/(ServiceSeeder.php + DatabaseSeeder.php update)

tests/Feature/
├── ServiceControllerTest.php

tests/Unit/Data/
├── ServiceCardDataTest.php
└── ServiceDetailDataTest.php
```

---

## Pattern 2: Новая форма (Callback/Contact)

### Prompt для AI

```
Создай форму обратной связи "Callback" (обратный звонок).

Требования:
- Model: Callback (name, phone, club_id, consent, processed, processed_at, honeypot)
- Form Request: CallbackRequest с валидацией (phone regex, honeypot empty, consent accepted)
- Controller: CallbackController@store (JSON response, 201 created)
- Mailable: CallbackNotification (queue, markdown template)
- Blade: x-forms.callback (Alpine form handler, honeypot, loading state, success toast)
- Alpine: form-handler component with CSRF, validation errors, rate limit handling
- Filament Resource: CallbackResource (view, export CSV, status toggle)
- Rate Limit: throttle:3,1

Сгенерируй:
1. Миграция + Model
2. Form Request
3. Controller + Route (api.php)
4. Mail + Queue
5. Blade компонент
6. Alpine form-handler
7. Filament Resource
8. Test: validation, success, rate limit, honeypot
```

---

## Pattern 3: Blade Component с Alpine.js

### Prompt для AI

```
Создай Alpine.js компонент для Video Background воспроизведения.

Requirements:
- IntersectionObserver-based (play when visible, pause when not)
- Fallback poster image
- Loading="lazy" для видео
- ARIA attributes
- Configurable src, poster, autoplay, loop
- Mobile fallback (poster only, no autoplay)

Сгенерируй:
1. Alpine.component('video-background')
2. Blade компонент x-video-background
3. Тест: play state on visibility change
```

---

## Pattern 4: Миграция Yii2 View в Laravel Blade

### Prompt для AI

```
Мигрируй Yii2 view "frontend/views/services/index.php" в Laravel Blade.

Original:
- Использует Yii2 helpers: Html::encode(), Url::to(), ArrayHelper::index()
- Вёрстка на Bootstrap (container, row, col-md-*)
- jQuery для AJAX фильтрации
- Скрипты и стили inline

Requirements:
- Blade компоненты: x-features.services.card, grid
- Layout: x-layout с SEOData
- Фильтрация: Alpine.js store, не jQuery
- Стили: Design Tokens, не Bootstrap
- SEO: SEOData, JSON-LD, OG, canonical
- Пагинация: x-ui.pagination

Не переноси:
- Bootstrap классы → замени на Tailwind v4 утилиты
- jQuery → замени на Alpine
- Yii2 helpers → замени на Laravel helpers/functions
- Inline скрипты → вынеси в resources/js/alpine/
- Inline стили → используй Design Tokens
```

---

## Pattern 5: SEO для новой страницы

### Prompt для AI

```
Добавь SEOData поддержку для страницы "home".

Requirements:
- SEOData DTO: title, description, canonical, ogImage, ogType, jsonLd, metaRobots
- JSON-LD для Organization schema
- OpenGraph: title, description, image, type, url
- Twitter Cards: summary_large_image
- Canonical: self URL
- Lang: ru
- x-seo.meta компонент в layout

Сгенерируй:
1. SEOData класс с Organization JSON-LD
2. Обновлённый x-seo.meta компонент
3. Интеграция в HomeController + pages/home.blade.php
4. Test: проверка meta tags в ответе
```

---

## Navigation

- [← Roadmap](./roadmap.md)
- [Prompts →](./prompts.md)
- [Cookbook →](./cookbook.md)
- [Testing →](./testing.md)