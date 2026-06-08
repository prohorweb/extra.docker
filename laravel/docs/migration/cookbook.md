"# Cookbook — готовые рецепты кода

---

## 1. Callback Form (End-to-End)

### Model + Migration
`$table->id();`
`$table->string('name');`
`$table->string('phone');`
`$table->foreignId('club_id')->constrained();`
`$table->boolean('consent')->default(false);`
`$table->boolean('processed')->default(false);`
`$table->timestamp('processed_at')->nullable();`
`$table->string('honeypot')->nullable();`
`$table->timestamps();`

### Form Request
```php
class CallbackRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'phone' => ['required', 'regex:/^\\\\+7 \\\\(\\\\d{3}\\\\) \\\\d{3}-\\\\d{2}-\\\\d{2}$/'],
            'club_id' => 'required|exists:clubs,id',
            'consent' => 'required|accepted',
            'honeypot' => 'nullable|size:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Укажите ваше имя',
            'phone.required' => 'Укажите номер телефона',
            'phone.regex' => 'Формат: +7 (XXX) XXX-XX-XX',
            'consent.accepted' => 'Необходимо согласие на обработку данных',
        ];
    }
}
```

### Controller
```php
class CallbackController extends Controller
{
    public function __construct(
        private readonly CallbackService $callbackService
    ) {}

    public function store(CallbackRequest $request): JsonResponse
    {
        $callback = $this->callbackService->create(
            CallbackData::fromRequest($request->validated())
        );

        Mail::to(config('mail.admin'))
            ->queue(new CallbackNotification($callback));

        return response()->json([
            'success' => true,
            'message' => 'Мы свяжемся с вами в ближайшее время',
        ], 201);
    }
}
```

### Mailable
```php
class CallbackNotification extends Mailable implements ShouldQueue
{
    use Queueable;

    public function __construct(public Callback $callback) {}

    public function build(): self
    {
        return $this
            ->subject('Новый обратный звонок: ' . $this->callback->name)
            ->markdown('emails.callback', [
                'callback' => $this->callback,
            ]);
    }
}
```

### Alpine Component
```blade
<div x-data=\"formHandler()\" class=\"space-y-4\">
    <form @submit.prevent=\"submit('/api/callback', $refs.form)\" x-ref=\"form\">
        <x-ui.input name=\"name\" placeholder=\"Имя\" required />
        <x-ui.input name=\"phone\" type=\"tel\" placeholder=\"+7 (___) ___-__-__\" x-mask=\"+7 (999) 999-99-99\" required />
        <x-ui.select name=\"club_id\" :options=\"$clubs\" required />
        <x-ui.checkbox name=\"consent\" label=\"Согласен на обработку данных\" required />
        <input type=\"text\" name=\"honeypot\" class=\"hidden\" tabindex=\"-1\" autocomplete=\"off\">
        <x-ui.button type=\"submit\" :disabled=\"loading\">
            <span x-show=\"!loading\">Заказать звонок</span>
            <span x-show=\"loading\">Отправка...</span>
        </x-ui.button>
    </form>
    <div x-show=\"success\" class=\"text-green-400\">{{ message }}</div>
    <div x-show=\"errors.general\" class=\"text-red-400\" x-text=\"errors.general\"></div>
</div>
```

---

## 2. Hero Section with Video Background

```blade
<x-sections.hero
    :video=\"$heroData->videoUrl ?? asset('video/hero.mp4')\"
    :poster=\"$heroData->posterUrl ?? asset('images/hero-poster.jpg')\"
    heading=\"ТВОЁ ТЕЛО.<br>НАШ РЕЗУЛЬТАТ.\"
    subheading=\"Премиум фитнес-клубы с лучшими тренерами\"
    :cta-primary=\"['text' => 'Записаться', 'url' => '#callback']\"
    :cta-secondary=\"['text' => 'Выбрать клуб', 'url' => route('club')]\"
/>
```

---

## 3. Media Library Integration

```php
// Model
use Spatie\\\\MediaLibrary\\\\HasMedia;
use Spatie\\\\MediaLibrary\\\\InteractsWithMedia;

class Service extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->width(400)->height(300)->sharpen(10);
                $this->addMediaConversion('preview')
                    ->width(800)->height(600);
                $this->addMediaConversion('og')
                    ->width(1200)->height(630);
            });
    }

    public function getFirstMediaUrl(string $conversion = 'thumb'): string
    {
        return $this->getFirstMediaUrl('images', $conversion)
            ?: asset('images/placeholder-' . $conversion . '.jpg');
    }
}
```

---

## 4. JSON-LD Structured Data

```php
// SEOData DTO
public function buildJsonLd(): array
{
    return match ($this->ogType) {
        'website' => [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => config('app.name'),
            'url' => config('app.url'),
            'logo' => asset('img/logo.svg'),
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => config('app.phone'),
                'contactType' => 'customer service',
            ],
        ],
        'service' => [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $this->title,
            'description' => $this->description,
            'provider' => ['@type' => 'Organization', 'name' => config('app.name')],
        ],
        'article' => [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $this->title,
            'description' => $this->description,
            'datePublished' => $this->publishedAt,
        ],
        default => [],
    };
}
```

---

## 5. SEO Meta Component (x-seo.meta)

```blade
{{-- SEO Meta Tags --}}
<title>{{ $seo->title }}</title>
<meta name=\"description\" content=\"{{ $seo->description }}\">
<link rel=\"canonical\" href=\"{{ $seo->canonical }}\">
<meta name=\"robots\" content=\"{{ $seo->metaRobots[0] ?? 'index' }},{{ $seo->metaRobots[1] ?? 'follow' }}\">

{{-- Open Graph --}}
<meta property=\"og:title\" content=\"{{ $seo->title }}\">
<meta property=\"og:description\" content=\"{{ $seo->description }}\">
<meta property=\"og:url\" content=\"{{ $seo->canonical }}\">
<meta property=\"og:image\" content=\"{{ $seo->ogImage ?? asset('images/og-default.jpg') }}\">
<meta property=\"og:type\" content=\"{{ $seo->ogType ?? 'website' }}\">
<meta property=\"og:locale\" content=\"ru_RU\">

{{-- Twitter Cards --}}
<meta name=\"twitter:card\" content=\"summary_large_image\">
<meta name=\"twitter:title\" content=\"{{ $seo->title }}\">
<meta name=\"twitter:description\" content=\"{{ $seo->description }}\">
<meta name=\"twitter:image\" content=\"{{ $seo->ogImage ?? asset('images/og-default.jpg') }}\">

{{-- JSON-LD Structured Data --}}
@if(!empty($seo->jsonLd))
    <script type=\"application/ld+json\">
        {!! json_encode($seo->jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endif
```

---

## Navigation

- [← Roadmap](./roadmap.md)
- [Patterns →](./patterns.md)
- [Testing →](./testing.md)
- [Troubleshooting →](./troubleshooting.md)"