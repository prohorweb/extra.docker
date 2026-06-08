# Component Taxonomy & Conventions

---

## Иерархия компонентов

```
resources/views/components/
├── ui/                    # Atomic — примитивы
│   ├── button.blade.php
│   ├── card.blade.php
│   ├── input.blade.php
│   ├── label.blade.php
│   ├── badge.blade.php
│   ├── modal.blade.php
│   ├── avatar.blade.php
│   ├── dropdown.blade.php
│   └── pagination.blade.php
├── sections/              # Molecular — блоки страницы
│   ├── hero.blade.php
│   ├── grid.blade.php
│   ├── cta.blade.php
│   ├── slider.blade.php
│   ├── stats.blade.php
│   └── testimonials.blade.php
├── features/              # Organism — доменные компоненты
│   ├── services/
│   │   ├── card.blade.php
│   │   ├── grid.blade.php
│   │   ├── detail.blade.php
│   │   └── filter.blade.php
│   ├── news/
│   │   ├── card.blade.php
│   │   ├── grid.blade.php
│   │   └── detail.blade.php
│   ├── shares/
│   ├── trainers/
│   ├── events/
│   ├── programs/
│   ├── jobs/
│   └── articles/
├── modals/                # Глобальные модальные окна
│   ├── callback.blade.php
│   ├── club-select.blade.php
│   └── success.blade.php
├── layouts/
│   ├── app.blade.php      # Основной layout (header + main + footer)
│   ├── guest.blade.php    # Для auth страниц (login, register)
│   └── empty.blade.php    # Минимальный (landing, maintenance)
├── forms/
│   ├── contact.blade.php
│   └── callback.blade.php
└── seo/
    └── meta.blade.php     # x-seo.meta
```

---

## Naming Conventions

| Тип | Префикс | Пример | Описание |
|-----|---------|--------|----------|
| UI Primitive | `x-ui.` | `x-ui.button` | Переиспользуемые атомы |
| Section | `x-sections.` | `x-sections.hero` | Композиция секций |
| Feature | `x-features.{domain}.` | `x-features.services.card` | Доменные компоненты |
| Modal | `x-modals.` | `x-modals.callback` | Глобальные модалки |
| Layout | `x-layouts.` | `x-layouts.app` | Layout компоненты |
| Form | `x-forms.` | `x-forms.callback` | Формы |
| SEO | `x-seo.` | `x-seo.meta` | SEO компоненты |

**Файлы**: kebab-case (`service-card.blade.php`)
**Классы**: PascalCase (`ServiceCardData`)
**Props**: camelCase (`$service`, `$ctaPrimary`)
**Slots**: kebab-case (`heading`, `cta-primary`)

---

## Slot Conventions

### Default Slot (`$slot`)
Основной контент компонента.
```blade
<x-ui.card>
    {{-- Это попадёт в $slot --}}
    <p>Контент карточки</p>
</x-ui.card>
```

### Named Slots (рекомендуемые имена)

| Slot | Назначение | Пример |
|------|------------|--------|
| `heading` | Заголовок секции/карточки | `<x-slot name="heading">Услуги</x-slot>` |
| `description` | Описание/подзаголовок | `<x-slot name="description">Выберите подходящую</x-slot>` |
| `image` / `media` | Изображение/видео | `<x-slot name="image"><img src="..."></x-slot>` |
| `content` | Основной контент (альтернатива $slot) | `<x-slot name="content">...</x-slot>` |
| `footer` | Нижняя часть (CTA, мета) | `<x-slot name="footer"><x-ui.button>...</x-ui.button></x-slot>` |
| `actions` | Группа действий | `<x-slot name="actions">...</x-slot>` |
| `badge` | Бейдж/тег | `<x-slot name="badge"><x-ui.badge>Hot</x-ui.badge></x-slot>` |
| `meta` | Мета-информация (автор, дата) | `<x-slot name="meta">...</x-slot>` |
| `before` / `after` | Контент до/после основного | `<x-slot name="before">...</x-slot>` |

### Boolean Slots (передаются как атрибуты)
```blade
<x-sections.hero :has-video="true" :has-cta="false" />
```

---

## Component API Documentation Template

Каждый компонент должен иметь PHPDoc блок в начале файла:

```blade
{{--
@component x-features.services.card
@prop ServiceCardData $service — Данные услуги (required)
@prop bool $interactive — Хover эффекты и курсор (default: true)
@prop string|null $variant — Вариант отображения: default, compact (default: default)
@slot image — Изображение услуги (optional)
@slot content — Основной контент (default: title + description)
@slot footer — CTA кнопка (optional)
@slot badge — Бейдж (optional: new, popular, sale)
--}}

<div class="card {{ $interactive ? 'card-interactive' : '' }} {{ $variant }}">
    {{-- ... --}}
</div>
```

---

## Примеры ключевых компонентов

### `x-ui.button`
```blade
{{--
@prop string $variant — brand, outline, ghost (default: brand)
@prop string $size — sm, md, lg (default: md)
@prop bool $disabled (default: false)
@prop string|null $href — если задан, рендерит <a>
@prop string $type — button, submit, reset (default: button)
@prop array $attributes — дополнительные атрибуты
--}}

@php
$classes = [
    'base' => 'inline-flex items-center justify-center gap-2 rounded-xl font-semibold transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none',
    'variant' => [
        'brand' => 'bg-[var(--color-brand)] text-[var(--color-brand-contrast)] hover:bg-[var(--color-brand-hover)] focus-visible:ring-[var(--color-brand)] shadow-[var(--shadow-glow-brand)]',
        'outline' => 'border-2 border-[var(--color-surface-border)] text-[var(--color-text-primary)] hover:bg-[var(--color-surface-elevated)] hover:border-[var(--color-brand)] focus-visible:ring-[var(--color-brand)]',
        'ghost' => 'text-[var(--color-text-secondary)] hover:text-[var(--color-text-primary)] hover:bg-[var(--color-surface-elevated)] focus-visible:ring-[var(--color-surface-border)]',
    ],
    'size' => [
        'sm' => 'px-4 py-2 text-sm rounded-lg',
        'md' => 'px-5 py-3 text-base rounded-xl',
        'lg' => 'px-8 py-4 text-lg rounded-2xl',
    ],
];
$tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }}
    {{ $attributes->merge(['class' => implode(' ', [
        $classes['base'],
        $classes['variant'][$variant] ?? $classes['variant']['brand'],
        $classes['size'][$size] ?? $classes['size']['md'],
    ])]) }}
    @if(!$href) type="{{ $type }}" @endif
    @if($disabled) disabled @endif
>
    {{ $slot }}
</{{ $tag }}>
```

### `x-ui.card`
```blade
{{--
@prop bool $interactive — hover эффекты (default: true)
@slot image — Изображение (optional)
@slot content — Основной контент (required)
@slot footer — Нижняя часть (optional)
@slot badge — Бейдж в углу (optional)
--}}

<article
    class="rounded-[var(--radius-card)] bg-[var(--color-surface-elevated)] border border-[var(--color-surface-border)] shadow-[var(--shadow-card)] transition-shadow duration-300 {{ $interactive ? 'hover:shadow-[var(--shadow-elevated)] cursor-pointer' : '' }}"
>
    @if($image)
        <div class="relative overflow-hidden rounded-t-[var(--radius-card)]">
            {{ $image }}
            @if($badge)
                <div class="absolute top-3 left-3">{{ $badge }}</div>
            @endif
        </div>
    @endif
    <div class="p-6">
        {{ $content }}
    </div>
    @if($footer)
        <div class="px-6 pb-6 pt-0">{{ $footer }}</div>
    @endif
</article>
```

### `x-ui.modal` (Portal + Focus Trap)
```blade
{{--
@prop bool $open — управление видимостью
@prop string $title — заголовок модалки
@prop string $size — sm, md, lg, xl, full (default: md)
@event close — emit при закрытии
--}}

@if($open)
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[var(--z-modal-backdrop)] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
        @click.self="$dispatch('close')"
        x-trap.noscroll="open"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-title"
    >
        <div
            class="w-full max-w-{{ $size }} bg-[var(--color-surface)] rounded-2xl shadow-[var(--shadow-elevated)] overflow-hidden"
        >
            <div class="flex items-center justify-between p-4 border-b border-[var(--color-surface-border)]">
                <h2 id="modal-title" class="text-xl font-heading font-bold">{{ $title }}</h2>
                <button
                    @click="$dispatch('close')"
                    class="text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)] text-2xl leading-none p-1"
                    aria-label="Закрыть"
                >
                    ×
                </button>
            </div>
            <div class="p-6">
                {{ $slot }}
            </div>
        </div>
    </div>
@endif
```

### `x-sections.grid`
```blade
{{--
@prop iterable $items — коллекция данных
@prop array $columns — responsive columns ['default' => 1, 'md' => 2, 'lg' => 3]
@prop int $gap — gap в Tailwind units (default: 6)
@slot card — карточка для каждого item (receives $item)
@slot empty — пустое состояние (optional)
--}}

@php
$gridClasses = 'grid gap-' . $gap;
foreach ($columns as $breakpoint => $cols) {
    $prefix = $breakpoint === 'default' ? '' : $breakpoint . ':';
    $gridClasses .= " {$prefix}grid-cols-{$cols}";
}
@endphp

<div class="{{ $gridClasses }}">
    @forelse($items as $item)
        @if(isset($slot))
            {{ $slot->render(['item' => $item]) }}
        @else
            <div>{{ $item }}</div>
        @endif
    @empty
        {{ $empty ?? '<div class="col-span-full text-center py-12 text-[var(--color-text-muted)]">Ничего не найдено</div>' }}
    @endforelse
</div>
```

---

## Composition Patterns

### Pattern 1: Section wraps Feature Grid
```blade
<x-sections.grid :items="$services" :columns="['default' => 1, 'md' => 2, 'lg' => 3]">
    <x-slot name="card" :item="$service">
        <x-features.services.card :service="$service" />
    </x-slot>
</x-sections.grid>
```

### Pattern 2: Feature Card uses UI Primitives
```blade
<x-ui.card :interactive="true">
    <x-slot name="image">
        <img src="{{ $service->imageUrl }}" alt="{{ $service->title }}" class="w-full h-48 object-cover">
    </x-slot>
    <x-slot name="content">
        <h3 class="text-lg font-heading font-bold mb-2">{{ $service->title }}</h3>
        <p class="text-[var(--color-text-secondary)] mb-4">{{ $service->shortDescription }}</p>
        @if($service->tags)
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach($service->tags as $tag)
                    <x-ui.badge variant="brand">{{ $tag }}</x-ui.badge>
                @endforeach
            </div>
        @endif
    </x-slot>
    <x-slot name="footer">
        <x-ui.button size="sm" href="{{ route('services.show', $service->slug) }}">Подробнее</x-ui.button>
    </x-slot>
</x-ui.card>
```

### Pattern 3: Modal with Form
```blade
<x-ui.modal :open="$modals->isOpen('callback')" title="Обратный звонок" @close="$modals->hideModal()">
    <x-forms.callback />
</x-ui.modal>
```

---

## Anti-Patterns (Что НЕ делать)

| ❌ Неправильно | ✅ Правильно |
|---------------|--------------|
| `<x-ui.card><div class="p-6">...</div></x-ui.card>` | Использовать slots: `image`, `content`, `footer` |
| Пробрасывать 10+ props в компонент | Группировать в Data объект или использовать slots |
| `@apply` цепочки > 3 утилит | Выносить в component class или CSS layer |
| Хардкодить цвета `bg-red-600` | Использовать токены `bg-[var(--color-brand)]` |
| Инлайн Alpine логика в Blade | Выносить в `Alpine.component()` |
| Дублировать разметку карточек | Создавать `x-features.{domain}.card` |

---

## Навигация

- [← Roadmap](./roadmap.md)
- [Foundation →](./foundation.md)
- [Data Layer →](./data-layer.md)
- [Patterns →](./patterns.md)