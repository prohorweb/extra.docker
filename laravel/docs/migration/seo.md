# SEO Infrastructure

---

## SEOData DTO

**Файл**: `app/Data/SEO/SEOData.php`

```php
<?php

namespace App\Data\SEO;

use App\Data\BaseData;
use App\Models\Concerns\HasSeo;

readonly class SEOData extends BaseData
{
    public function __construct(
        public string $title,
        public string $description,
        public string $canonical,
        public ?string $ogImage = null,
        public string $ogType = 'website',
        public string $ogLocale = 'ru_RU',
        public string $twitterCard = 'summary_large_image',
        public array $jsonLd = [],
        public array $metaRobots = ['index', 'follow'],
        public ?string $prevUrl = null,
        public ?string $nextUrl = null,
    ) {}

    public static function fromModel(HasSeo $model): self
    {
        return new self(
            title: $model->seo_title ?? $model->title,
            description: $model->seo_description ?? Str::limit(strip_tags($model->description), 160),
            canonical: $model->getCanonicalUrl(),
            ogImage: $model->getFirstMediaUrl('og') ?? config('app.og_image_default'),
            ogType: $model->getOgType(),
            jsonLd: $model->getJsonLd(),
            metaRobots: $model->seo_noindex ? ['noindex', 'nofollow'] : ['index', 'follow'],
        );
    }

    public static function forHomepage(): self
    {
        return new self(
            title: 'Extra Fitness — Премиум фитнес-клубы Москвы',
            description: 'Лучшие тренеры, современное оборудование, премиум сервис. Запишитесь на бесплатное пробное занятие.',
            canonical: url('/'),
            ogImage: asset('images/og-home.jpg'),
            ogType: 'website',
            jsonLd: [
                [
                    '@context' => 'https://schema.org',
                    '@type' => 'HealthClub',
                    'name' => 'Extra Fitness',
                    'url' => url('/'),
                    'logo' => asset('img/logo.svg'),
                    'description' => 'Премиум фитнес-клубы в Москве',
                    'address' => [
                        '@type' => 'PostalAddress',
                        'addressLocality' => 'Москва',
                        'addressCountry' => 'RU',
                    ],
                    'geo' => [
                        '@type' => 'GeoCoordinates',
                        'latitude' => 55.7558,
                        'longitude' => 37.6176,
                    ],
                    'openingHoursSpecification' => [
                        '@type' => 'OpeningHoursSpecification',
                        'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                        'opens' => '06:00',
                        'closes' => '23:00',
                    ],
                    'priceRange' => '$$$',
                ],
            ],
        );
    }

    public static function forServicesIndex(): self
    {
        return new self(
            title: 'Услуги и направления — Extra Fitness',
            description: 'Силовые тренировки, кардио, групповые программы, персональные тренировки, бассейн, сауна. Выберите своё направление.',
            canonical: url('/services'),
            ogImage: asset('images/og-services.jpg'),
            jsonLd: [
                [
                    '@context' => 'https://schema.org',
                    '@type' => 'ItemList',
                    'itemListElement' => [], // заполняется в контроллере
                ],
            ],
        );
    }
}
```

---

## x-seo.meta Component

**Файл**: `resources/views/components/seo/meta.blade.php`

```blade
{{--
@prop SEOData $seo
--}}

<title>{{ $seo->title }}</title>
<meta name="description" content="{{ $seo->description }}">
<link rel="canonical" href="{{ $seo->canonical }}">

@if($seo->prevUrl)
    <link rel="prev" href="{{ $seo->prevUrl }}">
@endif
@if($seo->nextUrl)
    <link rel="next" href="{{ $seo->nextUrl }}">
@endif

<meta name="robots" content="{{ implode(', ', $seo->metaRobots) }}">

<!-- Open Graph -->
<meta property="og:title" content="{{ $seo->title }}">
<meta property="og:description" content="{{ $seo->description }}">
<meta property="og:type" content="{{ $seo->ogType }}">
<meta property="og:url" content="{{ $seo->canonical }}">
<meta property="og:locale" content="{{ $seo->ogLocale }}">
@if($seo->ogImage)
    <meta property="og:image" content="{{ $seo->ogImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
@endif
<meta property="og:site_name" content="Extra Fitness">

<!-- Twitter Card -->
<meta name="twitter:card" content="{{ $seo->twitterCard }}">
<meta name="twitter:title" content="{{ $seo->title }}">
<meta name="twitter:description" content="{{ $seo->description }}">
@if($seo->ogImage)
    <meta name="twitter:image" content="{{ $seo->ogImage }}">
@endif

<!-- JSON-LD -->
@if(!empty($seo->jsonLd))
    <script type="application/ld+json">
        {{ json_encode($seo->jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}
    </script>
@endif
```

---

## Usage in Layouts

**`resources/views/components/layouts/app.blade.php`**

```blade
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @isset($seo)
        <x-seo.meta :seo="$seo" />
    @else
        <title>{{ config('app.name', 'Extra Fitness') }}</title>
        <meta name="description" content="{{ config('app.description', 'Премиум фитнес-клубы Москвы') }}">
        <link rel="canonical" href="{{ url()->current() }}">
    @endisset

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
```

---

## Model SEO Contract

**Файл**: `app/Models/Concerns/HasSeo.php`

```php
<?php

namespace App\Models\Concerns;

use App\Data\SEO\SEOData;

trait HasSeo
{
    public function getCanonicalUrl(): string
    {
        return url($this->getRouteKey());
    }

    public function getOgType(): string
    {
        return 'website';
    }

    public function getJsonLd(): array
    {
        return [];
    }
}
```

**Пример использования в модели Service:**

```php
class Service extends Model
{
    use HasSeo;

    public function getRouteKey(): string
    {
        return "services/{$this->slug}";
    }

    public function getOgType(): string
    {
        return 'service';
    }

    public function getJsonLd(): array
    {
        return [
            [
                '@context' => 'https://schema.org',
                '@type' => 'Service',
                'name' => $this->title,
                'description' => strip_tags($this->description),
                'url' => $this->getCanonicalUrl(),
                'image' => $this->getFirstMediaUrl('og') ?? config('app.og_image_default'),
                'provider' => [
                    '@type' => 'Organization',
                    'name' => 'Extra Fitness',
                    'url' => url('/'),
                ],
                'areaServed' => [
                    '@type' => 'City',
                    'name' => 'Москва',
                ],
                'offers' => $this->price_from ? [
                    '@type' => 'Offer',
                    'name' => $this->title,
                    'price' => $this->price_from,
                    'priceCurrency' => 'RUB',
                    'availability' => 'https://schema.org/InStock',
                ] : null,
            ],
        ];
    }
}
```

---

## JSON-LD Types by Page

| Страница | Schema.org Type | Компонент |
|----------|-----------------|-----------|
| Homepage | `HealthClub`, `Organization` | `SEOData::forHomepage()` |
| Services Index | `ItemList` + `Service` | `SEOData::forServicesIndex()` |
| Service Show | `Service` | `ServiceDetailData->seo` |
| News Index | `ItemList` + `Article` | `SEOData::forNewsIndex()` |
| News Show | `Article`, `NewsArticle` | `NewsDetailData->seo` |
| Trainer Show | `Person` | `TrainerDetailData->seo` |
| Event Show | `Event` | `EventDetailData->seo` |
| Job Show | `JobPosting` | `JobDetailData->seo` |
| Article Show | `Article` | `ArticleDetailData->seo` |
| Breadcrumb | `BreadcrumbList` | `x-seo.breadcrumb` |

---

## Breadcrumb Component

**Файл**: `resources/views/components/seo/breadcrumb.blade.php`

```blade
{{--
@prop array $items — [['label' => 'Главная', 'url' => '/'], ['label' => 'Услуги']]
--}}

@php
$jsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => collect($items)->map(function ($item, $index) {
        return [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $item['label'],
            'item' => isset($item['url']) ? url($item['url']) : null,
        ];
    })->filter(fn($i) => $i['item'] !== null)->values()->toArray(),
];
@endphp

<script type="application/ld+json">
    {{ json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}
</script>

<nav aria-label="Хлебные крошки" class="mb-6">
    <ol class="flex items-center gap-2 text-sm text-[var(--color-text-muted)]">
        @foreach($items as $index => $item)
            @if($index > 0)
                <span class="mx-2">/</span>
            @endif
            @if(isset($item['url']) && $index < count($items) - 1)
                <a href="{{ url($item['url']) }}" class="hover:text-[var(--color-brand)]">{{ $item['label'] }}</a>
            @else
                <span aria-current="page">{{ $item['label'] }}</span>
            @endif
        @endforeach
    </ol>
</nav>
```

---

## Sitemap & Robots

### Sitemap (Spatie Laravel Sitemap)

**config/sitemap.php**

```php
return [
    'links' => [
        [
            'url' => env('APP_URL') . '/',
            'changefreq' => 'daily',
            'priority' => 1.0,
        ],
    ],
    'models' => [
        [
            'model' => App\Models\Service::class,
            'query' => fn($q) => $q->active()->with('media'),
            'url' => fn($model) => $model->getCanonicalUrl(),
            'changefreq' => 'weekly',
            'priority' => 0.8,
            'lastmod' => 'updated_at',
            'images' => fn($model) => $model->getMedia('images')->map(fn($m) => $m->getUrl())->toArray(),
        ],
        // ... News, Share, Trainer, Event, Article, Job
    ],
];
```

**Генерация**: `php artisan sitemap:generate` (в scheduler ежедневно)

### Robots.txt

**Файл**: `resources/views/robots.blade.php`

```blade
User-agent: *
Disallow: /admin/
Disallow: /api/
Disallow: /_ignition/
Disallow: /storage/
Allow: /

Sitemap: {{ url('sitemap.xml') }}
Host: {{ parse_url(env('APP_URL'), PHP_URL_HOST) }}
```

**Route**: `Route::get('/robots.txt', fn() => response()->view('robots')->header('Content-Type', 'text/plain'));`

---

## Performance SEO

| Метрика | Target | Инструмент |
|---------|--------|------------|
| LCP | < 2.5s | Lighthouse |
| CLS | < 0.1 | Lighthouse |
| FID | < 100ms | Lighthouse |
| Core Web Vitals | Все зелёные | PageSpeed Insights |

**Оптимизации**:
- Self-hosted variable fonts (Oswald, Roboto, Plus Jakarta Sans) с `preload`
- AVIF/WebP через Media Library conversions
- Critical CSS inline (Vite plugin)
- Defer non-critical JS
- Service Worker для кэширования (Workbox)

---

## Навигация

- [← Roadmap](./roadmap.md)
- [Foundation →](./foundation.md)
- [Data Layer →](./data-layer.md)
- [Components →](./components.md)
- [Deployment →](./deployment.md)
"