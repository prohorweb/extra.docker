# UI Foundation & Design System

---

## Design Tokens — Single Source of Truth

Все дизайн-токены определены в `resources/css/app.css` через Tailwind v4 `@theme`.
Никаких magic numbers в компонентах — **только токены**.

### Цветовая система

| Токен | Значение | Назначение |
|-------|----------|------------|
| `--color-brand-500` | `#c8102e` | Primary brand (красный Extra Fitness) |
| `--color-brand-600` | `#991b1b` | Hover state |
| `--color-brand-100` | `#fee2e2` | Light backgrounds |
| `--color-brand-contrast` | `#ffffff` | Текст на brand фоне |
| `--color-surface` | `#0a0f1a` | Основной фон (dark) |
| `--color-surface-elevated` | `#111827` | Карточки, модалки |
| `--color-surface-border` | `#1f2937` | Границы |
| `--color-text-primary` | `#f9fafb` | Основной текст |
| `--color-text-secondary` | `#d1d5db` | Вторичный текст |
| `--color-text-muted` | `#9ca3af` | Placeholder, disabled |
| `--color-success` | `#22c55e` | Успех |
| `--color-warning` | `#f59e0b` | Предупреждение |
| `--color-danger` | `#ef4444` | Ошибка |

### Типографика

| Токен | Значение | Назначение |
|-------|----------|------------|
| `--font-heading` | `"Oswald", system-ui, sans-serif` | Заголовки (h1-h6) |
| `--font-body` | `"Roboto", system-ui, sans-serif` | Основной текст |
| `--font-ui` | `var(--font-body)` | UI элементы (кнопки, инпуты) |
| `--text-display-xl` | `clamp(2.5rem, 5vw, 4rem)` | Hero заголовок |
| `--text-display-lg` | `clamp(2rem, 4vw, 3rem)` | Section заголовок |
| `--text-display-md` | `clamp(1.5rem, 3vw, 2.25rem)` | Card заголовок |
| `--text-base` | `1rem` | Body text |
| `--text-sm` | `0.875rem` | Small text |
| `--font-weight-bold` | `700` | Headings |
| `--font-weight-semibold` | `600` | Button text |

### Spacing & Radius

| Токен | Значение |
|-------|----------|
| `--spacing-18` | `4.5rem` (72px) |
| `--spacing-22` | `5.5rem` (88px) |
| `--spacing-30` | `7.5rem` (120px) |
| `--radius-card` | `1.5rem` (24px) |
| `--radius-section` | `2rem` (32px) |
| `--radius-full` | `9999px` |

### Shadows & Transitions

| Токен | Значение |
|-------|----------|
| `--shadow-card` | `0 4px 24px -4px rgb(10 15 26 / 0.4)` |
| `--shadow-elevated` | `0 20px 40px -15px rgb(10 15 26 / 0.6)` |
| `--shadow-glow-brand` | `0 0 40px -10px rgb(200 16 46 / 0.35)` |
| `--transition-fast` | `150ms ease` |
| `--transition-base` | `200ms ease` |
| `--transition-slow` | `300ms ease` |

### Z-Index Scale

| Токен | Значение | Назначение |
|-------|----------|------------|
| `--z-dropdown` | `100` | Dropdown menus |
| `--z-sticky` | `200` | Sticky header |
| `--z-modal-backdrop` | `300` | Modal backdrop |
| `--z-modal` | `400` | Modal content |
| `--z-toast` | `500` | Toasts/Notifications |

---

## Component Taxonomy

```
resources/views/components/
├── ui/                    # Примитивы (atomic)
│   ├── button.blade.php
│   ├── card.blade.php
│   ├── input.blade.php
│   ├── label.blade.php
│   ├── badge.blade.php
│   └── modal.blade.php
├── sections/              # Крупные блоки страницы (molecular)
│   ├── hero.blade.php
│   ├── grid.blade.php
│   ├── cta.blade.php
│   └── slider.blade.php
├── features/              # Доменные компоненты (organism)
│   ├── services/
│   │   ├── card.blade.php
│   │   ├── grid.blade.php
│   │   └── detail.blade.php
│   ├── news/
│   ├── shares/
│   ├── trainers/
│   ├── events/
│   ├── programs/
│   ├── jobs/
│   └── articles/
├── modals/                # Глобальные модалки
│   ├── callback.blade.php
│   └── club-select.blade.php
├── layouts/
│   ├── app.blade.php
│   ├── guest.blade.php
│   └── empty.blade.php
└── forms/
    ├── contact.blade.php
    └── callback.blade.php
```

---

## UI Primitives — `x-ui.*`

### `x-ui.button`
```blade
<x-ui.button variant="brand" size="lg" :disabled="$loading" wire:click="submit">
    Записаться на пробное
</x-ui.button>
```

| Prop | Type | Default | Variants |
|------|------|---------|----------|
| `variant` | string | `brand` | `brand`, `outline`, `ghost` |
| `size` | string | `md` | `sm`, `md`, `lg` |
| `disabled` | bool | `false` | — |
| `href` | string? | `null` | Если задан — рендерит `<a>` |
| `type` | string | `button` | `button`, `submit`, `reset` |

**CSS Tokens Used**: `--color-brand`, `--color-brand-hover`, `--color-surface-border`, `--radius-card`, `--shadow-glow-brand`, `--transition-base`

### `x-ui.card`
```blade
<x-ui.card class="hover:shadow-[var(--shadow-elevated)]">
    <x-slot name="image">
        <img src="{{ $service->imageUrl }}" alt="{{ $service->title }}">
    </x-slot>
    <x-slot name="content">
        <h3>{{ $service->title }}</h3>
        <p>{{ $service->shortDescription }}</p>
    </x-slot>
    <x-slot name="footer">
        <x-ui.button size="sm" href="{{ $service->url }}">Подробнее</x-ui.button>
    </x-slot>
</x-ui.card>
```

**Slots**: `image`, `content`, `footer`, `badge`

### `x-ui.input` + `x-ui.label`
```blade
<x-ui.label for="phone" :required="true">Телефон</x-ui.label>
<x-ui.input id="phone" name="phone" type="tel" placeholder="+7 (XXX) XXX-XX-XX" required />
```

### `x-ui.badge`
```blade
<x-ui.badge variant="brand">Новинка</x-ui.badge>
<x-ui.badge variant="success">Активно</x-ui.badge>
```

### `x-ui.modal` (Portal-based, Focus Trap, ESC)
```blade
<x-ui.modal :open="$modalOpen" @close="$modalOpen = false" title="Обратный звонок">
    <x-features.forms.callback />
</x-ui.modal>
```

---

## Section Components — `x-sections.*`

### `x-sections.hero`
```blade
<x-sections.hero
    :video="$heroData->videoUrl"
    :poster="$heroData->posterUrl"
    :heading="$heroData->heading"
    :subheading="$heroData->subheading"
    :cta-primary="['text' => 'Записаться', 'url' => '#callback']"
    :cta-secondary="['text' => 'Выбрать клуб', 'url' => route('club')]"
/>
```

**Props**: `video`, `poster`, `heading` (HTML allowed), `subheading`, `ctaPrimary`, `ctaSecondary`

### `x-sections.grid`
```blade
<x-sections.grid
    :items="$services"
    :columns="['default' => 1, 'md' => 2, 'lg' => 3]"
    :gap="8"
>
    <x-slot name="card" :item="$item">
        <x-features.services.card :service="$item" />
    </x-slot>
</x-sections.grid>
```

### `x-sections.cta`
```blade
<x-sections.cta
    heading="Готовы начать?"
    description="Запишитесь на бесплатное пробное занятие"
    :actions="[['text' => 'Записаться', 'variant' => 'brand', 'url' => '#callback']]"
/>
```

---

## Alpine.js Architecture

### Stores (`resources/js/alpine/stores/`)

```js
// navigation.js
Alpine.store('navigation', {
    mobileMenuOpen: false,
    dropdownOpen: null,
    scrolled: false,
    
    toggleMobileMenu() { this.mobileMenuOpen = !this.mobileMenuOpen },
    closeMobileMenu() { this.mobileMenuOpen = false },
    toggleDropdown(key) { this.dropdownOpen = this.dropdownOpen === key ? null : key },
    onScroll() { this.scrolled = window.scrollY > 20 },
    init() { window.addEventListener('scroll', () => this.onScroll()) }
});
```

```js
// modals.js
Alpine.store('modals', {
    currentModal: null,
    
    showModal(name) { this.currentModal = name; document.body.style.overflow = 'hidden' },
    hideModal() { this.currentModal = null; document.body.style.overflow = '' },
    
    isOpen(name) { return this.currentModal === name }
});
```

### Components (`resources/js/alpine/components/`)

```js
// VideoBackground.js
Alpine.component('video-background', (el) => ({
    init() {
        this.video = el.querySelector('video');
        this.setupIntersectionObserver();
    },
    setupIntersectionObserver() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                this.video[entry.isIntersecting ? 'play' : 'pause']().catch(() => {});
            });
        }, { rootMargin: '100px' });
        observer.observe(el);
    }
}));
```

```js
// FormHandler.js
Alpine.component('form-handler', (el) => ({
    loading: false,
    errors: {},
    
    async submit(url, formData) {
        this.loading = true;
        this.errors = {};
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify(formData)
            });
            const data = await response.json();
            if (!response.ok) throw data;
            this.onSuccess(data);
        } catch (e) {
            this.errors = e.errors || { general: e.message };
        } finally {
            this.loading = false;
        }
    }
}));
```

---

## Usage Rules

| Правило | Описание |
|---------|----------|
| **Tokens Only** | В компонентах только `var(--token)`, никаких `bg-red-600` |
| **Slots > Props** | Для контента используйте slots, для конфигурации — props |
| **No @apply > 3** | Максимум 3 утилиты в `@apply`, иначе — component class |
| **Alpine Isolation** | Каждый компонент — изолированный `Alpine.component` |
| **Store for Shared State** | Глобальное состояние (modals, navigation) — только через `Alpine.store` |

---

## Навигация

- [← Roadmap](./roadmap.md)
- [Data Layer →](./data-layer.md)
- [Components →](./components.md)
- [Patterns →](./patterns.md)