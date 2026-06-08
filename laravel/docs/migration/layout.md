# Layout Architecture

## 1. Общая структура `resources/views/`

```
resources/views/
├── components/                     # Переиспользуемые компоненты
│   ├── layouts/                    # Обёртки и инфраструктурные части
│   │   ├── app.blade.php           # Основной layout (header + main + footer + модалки + theme CSS)
│   │   └── parts/
│   │       ├── header.blade.php    # Навигация, логотип
│   │       └── footer.blade.php    # Подвал
│   ├── sections/                   # Контентные блоки (переставляемые, не привязаны к домену)
│   │   ├── hero.blade.php
│   │   ├── grid.blade.php
│   │   ├── cta.blade.php
│   │   ├── map.blade.php
│   │   ├── slider.blade.php
│   │   └── clubs-carousel/
│   │       ├── desktop.blade.php
│   │       └── mobile.blade.php
│   ├── features/                   # Специализированные sections для доменных сущностей
│   │   ├── services/               # (появится с миграцией услуг)
│   │   ├── news/                   # (появится с миграцией новостей)
│   │   └── ...
│   ├── modals/                     # Глобальные модальные окна
│   │   ├── callback.blade.php
│   │   └── club-select.blade.php
│   ├── forms/                      # Инлайн-формы
│   │   ├── contact.blade.php
│   │   └── callback.blade.php
│   ├── ui/                         # Примитивы
│   │   ├── button.blade.php
│   │   ├── card.blade.php
│   │   ├── input.blade.php
│   │   ├── label.blade.php
│   │   ├── badge.blade.php
│   │   └── modal.blade.php
│   └── seo/
│       └── meta.blade.php
├── pages/                          # Страницы-композиции
│   ├── welcome.blade.php           # extra.new — выбор клуба
│   └── home.blade.php              # {club}.extra.new, de-vision.new — страница клуба
├── layouts/                        # (временно, будет заменено на components/layouts/)
│   └── app.blade.php               # Уже переписан в компонентном стиле
└── welcome.blade.php               # (Vite test — пока оставить)
```

**Удалены**: `partials/`, `sections/`, `subdomain/` — содержимое перенесено в компоненты.

---

## 2. Доменная зона .new

Все домены работают в зоне `.new`:

| Домен | Страница | Описание |
|-------|----------|----------|
| `extra.new` | `pages.welcome` | Главная — выбор клуба |
| `www.extra.new` | `pages.welcome` | Главная — выбор клуба |
| `piter.extra.new` | `pages.home` | Клуб ТРЦ «Питер» |
| `matros.extra.new` | `pages.home` | Клуб «Матроса Железняка» |
| `de-vision.new` | `pages.home` | Клуб De-Vision |
| `www.de-vision.new` | `pages.home` | Клуб De-Vision |

Смена контента между клубами — через переключение БД и CSS-переменных, **шаблон остаётся тем же** (`pages.home`).

---

## 3. Взаимодействие с роутингом и контроллерами

### Текущий роутинг

```php
// routes/web.php
Route::get('/', [HomeController::class, 'index'])->name('home');
```

### Контроллер (HomeController)

```php
class HomeController extends Controller
{
    public function index(Request $request)
    {
        $host = $request->getHost();
        
        // Демо-данные клубов и меток карты
        $demoClubs = [...];
        $demoPlacemarks = [...];

        if (in_array($host, ['extra.new', 'www.extra.new'])) {
            return view('pages.welcome', [
                'hero' => [...],
                'clubs' => $demoClubs,
                'placemarks' => $demoPlacemarks,
            ]);
        }

        if (in_array($host, ['de-vision.new', 'www.de-vision.new']) 
            || str_contains($host, '.extra.new')) {
            return view('pages.home', [
                'club' => [...],
                'services' => [...],
                'theme' => [],
            ]);
        }

        return view('pages.welcome', ['clubs' => $demoClubs, 'placemarks' => $demoPlacemarks]);
    }
}
```

### Будущие роуты

| Роут | Страница | Контроллер |
|------|----------|-----------|
| `/` | `pages.welcome` / `pages.home` | `HomeController@index` |
| `/services` | `pages.services.index` | `ServiceController@index` |
| `/services/{slug}` | `pages.services.show` | `ServiceController@show` |
| `/news` | `pages.news.index` | `NewsController@index` |
| `/contact` | `pages.contact` | `ContactController@index` |

---

## 4. Layout — `layouts/app.blade.php`

```blade
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <x-seo.meta :data="$seo ?? null" />
    @if(isset($theme) && is_array($theme))
        <style>
            :root { @foreach($theme as $key => $value) {{ $key }}: {{ $value }}; @endforeach }
        </style>
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="bg-black text-white antialiased">
    @include('components.layouts.parts.header')
    <main>{{ $slot }}</main>
    @include('components.layouts.parts.footer')
    <x-modals.callback />
    <x-modals.club-select />
    @stack('scripts')
</body>
</html>
```

**Правила**:
- Header/footer — только через layout, никогда из страниц.
- Глобальные модалки (callback, club-select) — всегда присутствуют, открываются через `$dispatch`.
- Тема клуба — через `$theme` в `:root { ... }`.
- SEO — каждая страница передаёт `$seo`.

---

## 5. Страницы (pages/)

### `pages/welcome.blade.php` (extra.new)

```blade
<x-layouts.app :seo="$seo ?? null">
    <x-sections.hero
        :video="$hero['video'] ?? '/video/bg_moution.mp4'"
        :logo="$hero['logo'] ?? asset('img/logo.svg')"
        :heading="$hero['heading'] ?? 'Сеть фитнес клубов на результат!'"
        :cta="$hero['cta'] ?? ['text' => 'Выберите клуб', 'url' => '#clubs']"
    />
    <x-sections.clubs-carousel.desktop :clubs="$clubs ?? []" target="clubs" />
    <x-sections.clubs-carousel.mobile :clubs="$clubs ?? []" target="clubs-mobile" />
    <x-sections.map :placemarks="$placemarks ?? []" />
</x-layouts.app>
```

### `pages/home.blade.php` ({club}.extra.new, de-vision.new)

```blade
<x-layouts.app :seo="$seo ?? null" :theme="$theme ?? []">
    <x-sections.hero :heading="$club['name'] ?? 'Фитнес клуб'" ... />
    @if(isset($services))
        <x-sections.grid :items="$services" :columns="['default' => 1, 'md' => 2, 'lg' => 3]">
            <x-slot name="card" :item="$service">
                <x-ui.card>
                    <x-slot name="content">
                        <h3>{{ $service['title'] }}</h3>
                    </x-slot>
                </x-ui.card>
            </x-slot>
        </x-sections.grid>
    @endif
    <x-sections.cta heading="Готовы начать?" ... />
</x-layouts.app>
```

---

## 6. sections vs features

| | **sections/** | **features/** |
|---|---|---|
| **Назначение** | Универсальные блоки для любых страниц | Блоки для конкретной доменной сущности |
| **Примеры** | hero, grid, cta, map, slider, clubs-carousel | services.card, services.grid, news.card |
| **Когда создавать** | Сразу, по мере необходимости | Когда появляется сущность (Service, News) |
| **Правило** | Если сомневаетесь — создавайте sections/ | Если блок привязан к модели — features/{entity}/ |

---

## 7. Текущий статус рефакторинга

| Шаг | Действие | Статус |
|-----|----------|--------|
| 1 | Создать папки структуры components/ | ✅ |
| 2 | Перенести header/footer в layouts/parts/ | ✅ |
| 3 | Удалить старые header/footer из корня components/ | ✅ |
| 4 | Написать UI-примитивы (button, card, input, label, badge, modal) | ✅ |
| 5 | Написать section-компоненты (hero, map, clubs-carousel, grid, cta) | ✅ |
| 6 | Написать seo/meta, modals (callback, club-select) | ✅ |
| 7 | Обновить layouts/app.blade.php (компонентный стиль, theme CSS, модалки) | ✅ |
| 8 | Обновить pages/welcome.blade.php и pages/home.blade.php | ✅ |
| 9 | Обновить HomeController с демо-данными | ✅ |
| 10 | Удалить partials/, sections/, subdomain/ | ✅ |
| 11 | Создать docs/migration/layout.md | ✅ |

---

## Ссылки

- [Components →](./components.md) — таксономия и API компонентов
- [Foundation →](./foundation.md) — дизайн-токены (будет синхронизировано)
- [Data Layer →](./data-layer.md) — DTO и сервисы (отложено)