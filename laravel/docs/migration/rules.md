# Coding Rules & Definition of Done

---

## Git Workflow

### Branch Naming
```
main              — production ready
develop           — integration branch
feature/{phase}-{short-desc}  — feature/{phase-2}-homepage-backend
hotfix/{issue}    — hotfix/callback-validation
release/{version} — release/v1.0.0
```

### Commit Convention (Conventional Commits)
```
feat(homepage): add hero section with video background
fix(forms): callback validation error messages
docs(migration): update phase 6 exit criteria
refactor(components): extract button variants
test(services): add feature test for index page
style(ui): adjust card hover shadow
chore(deps): update filament to v3.3
```

### PR Template

**File**: `.github/pull_request_template.md`

```markdown
## Description
Brief description of changes and which phase they belong to.

## Type of Change
- [ ] Feature (new functionality)
- [ ] Bug fix
- [ ] Refactor
- [ ] Documentation
- [ ] Tests

## Checklist
- [ ] Code follows project coding standards (see [rules.md](./docs/migration/rules.md))
- [ ] DTO pattern used, no Eloquent in Blade
- [ ] Design Tokens used (no hardcoded colors/spacing)
- [ ] No Bootstrap or jQuery classes/scripts
- [ ] SEOData added for new pages
- [ ] Responsive design verified (mobile/tablet/desktop)
- [ ] Feature/Unit tests added
- [ ] Lighthouse check: Perf > 90, SEO > 95
- [ ] Filament resource created (if applicable)

## Related Issues
Closes #ISSUE_NUMBER

## Screenshots (if applicable)

## Notes for Reviewer
```

### Code Review Checklist
```markdown
- [ ] No inline styles/scripts in Blade
- [ ] @apply chains ≤ 3 tokens
- [ ] Alpine stores for global state, not inline
- [ ] All forms have honeypot and rate limiting
- [ ] Mailable uses queue, not sync
- [ ] Form Requests handle validation
- [ ] No direct SQL queries in controllers
```

---

## Blade Rules

### ✅ Allowed
```blade
<x-ui.card>
    <x-slot name="content">
        <h3 class="text-lg font-heading">{{ $service->title }}</h3>
    </x-slot>
</x-ui.card>
```

### ❌ Forbidden
```blade
{{-- NO inline styles --}}
<h3 style="font-size: 1.25rem; color: #333;">{{ $service->title }}</h3>

{{-- NO direct Eloquent in Blade --}}
{{-- Bad --}}
<h3>{{ \\App\\Models\\Service::find(1)->title }}</h3>

{{-- Bad --}}
<x-ui.button :disabled="auth()->user()?->cannot('view', $service)" />

{{-- NO Bootstrap classes --}}
<div class="container row btn btn-primary">...</div>

{{-- NO @apply chains > 3 --}}
{{-- Bad --}}
<div class="@apply bg-white rounded-lg shadow-md p-6 border border-gray-200 hover:shadow-lg"></div>
```

### Guidelines
| Rule | Description |
|------|-------------|
| **Components > Direct HTML** | Use `x-ui.*`, `x-features.*` over raw HTML |
| **Slots > Props** | For content, use slots; for config, use props |
| **One Component Per File** | One `.blade.php` per component |
| **PHPDoc Header** | Document all components with props and slots |
| **No Inline Alpine** | Move complex logic to `Alpine.component()` |
| **No Inline Scripts** | Move JS to `resources/js/` |
| **No Inline Styles** | Use Design Tokens |

---

## CSS / Tailwind Rules

### ✅ Allowed
```css
/* Design Tokens in @theme */
@theme {
    --color-brand: #c8102e;
}

/* Component class (when >3 utilities) */
.card-featured {
    @apply rounded-[var(--radius-card)] bg-[var(--color-surface-elevated)];
    @apply border border-[var(--color-surface-border)];
    @apply shadow-[var(--shadow-card)] hover:shadow-[var(--shadow-elevated)];
}

/* @apply max 3 utilities for simple variants */
.btn-sm {
    @apply px-4 py-2 text-sm rounded-lg;
}
```

### ❌ Forbidden
```css
/* NO Bootstrap classes */
.my-element {
    @apply container row col-md-6;
}

/* NO hardcoded colors */
.my-element {
    color: #333;
    background-color: #f0f0f0;
}

/* NO @apply chains > 3 utilities */
.my-element {
    @apply bg-white rounded-lg shadow-md p-6 border border-gray-200 hover:shadow-lg transition-all duration-300;
}

/* NO !important */
.my-element {
    color: #000 !important;
}
```

### Guidelines
| Rule | Description |
|------|-------------|
| **Tokens Only** | Use `var(--color-brand)` not `text-red-600` |
| **@apply ≤ 3** | Max 3 utilities; otherwise, use component class |
| **No Bootstrap** | Completely removed from the project |
| **No !important** | Use specificity instead |
| **Minify in Prod** | `npm run build` handles minification |

---

## JavaScript / Alpine Rules

### ✅ Allowed
```javascript
// Store for global state
Alpine.store('navigation', {
    scrolled: false,
    mobileMenuOpen: false,
    init() {
        window.addEventListener('scroll', () => this.scrolled = window.scrollY > 20);
    }
});

// Component isolation
Alpine.component('video-background', (el) => ({
    init() { /* ... */ }
}));
```

### ❌ Forbidden
```javascript
// NO jQuery
$('.element').hide();

// NO inline event handlers in Blade
<button onclick="alert('Hello')">Click</button>

// NO global variables
window.myData = { ... };

// NO direct DOM manipulation
const element = document.getElementById('my-id');

element.style.display = 'none';
```

### Guidelines
| Rule | Description |
|------|-------------|
| **Zero jQuery** | Not included, not used |
| **Alpine Stores for Global State** | Navigation, modals, theme |
| **Alpine Components for Isolated Logic** | Video playback, form handlers |
| **No Inline Alpine** | Move to `resources/js/alpine/` |
| **Modern APIs** | Use `fetch`, `IntersectionObserver`, `CSS Variables` |

---

## PHP / Laravel Rules

### ✅ Allowed
```php
// DTO-first approach
$data = HomepageData::fromRequest();
return view('pages.home', ['data' => $data]);

// Service layer
class ServiceService {
    public function getFeatured(int $limit = 6): Collection { ... }
}

// Form Requests
class CallbackRequest extends FormRequest {
    public function rules(): array { return [...]; }
}
```

### ❌ Forbidden
```php
// NO raw queries in Controllers
$services = DB::select('SELECT * FROM services WHERE active = 1');

// NO business logic in Blade
// {{ \\App\\Models\\Service::whereActive(1)->get() }}

// NO logic in Form Request rules
public function rules() {
    return ['phone' => 'required|regex:/^\\+7 \\(\\d{3}\\) \\d{3}-\\d{2}-\\d{2}$/'];
}
```

### Guidelines
| Rule | Description |
|------|-------------|
| **DTO-first** | Blade receives only Data classes |
| **Service Layer** | Business logic in `app/Services/` |
| **Form Requests** | Validation in dedicated classes |
| **Mail + Queue** | All notifications go through queue |
| **Rate Limiting** | All forms: `throttle:3,1` |
| **Honeypot** | Anti-spam field on all forms |

---

## Definition of Done (Per Feature)

A feature is **DONE** only if:

### ✅ UI Migrated
- [ ] Blade components use Design Tokens
- [ ] Responsive design (mobile/tablet/desktop)
- [ ] No legacy Bootstrap/jQuery
- [ ] Alpine.js handles interactivity

### ✅ SEO Migrated
- [ ] `SEOData` passed to layout
- [ ] Canonical URL set
- [ ] OG Tags present
- [ ] JSON-LD structured data
- [ ] Meta description optimized

### ✅ Functional
- [ ] All links work
- [ ] Forms submit correctly
- [ ] Rate limiting applied
- [ ] Honeypot present
- [ ] Email notifications sent via queue

### ✅ Performance
- [ ] Lighthouse Performance > 90
- [ ] Lighthouse SEO > 95
- [ ] Lighthouse Accessibility > 95
- [ ] Lighthouse Best Practices > 90

### ✅ Tests
- [ ] Feature test: page loads successfully (200)
- [ ] Feature test: form validation (if applicable)
- [ ] Feature test: rate limiting (if applicable)
- [ ] Unit test: DTO `fromModel()` methods
- [ ] Unit test: Service methods

### ✅ Admin Panel (if applicable)
- [ ] Filament resource created
- [ ] Table with columns and filters
- [ ] Form for create/edit
- [ ] Media library integration
- [ ] Export functionality

---

## Navigation

- [← Roadmap](./roadmap.md)
- [Patterns →](./patterns.md)
- [Cookbook →](./cookbook.md)
- [Testing →](./testing.md)