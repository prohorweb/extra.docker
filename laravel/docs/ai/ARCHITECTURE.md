# Architecture — Yii2 Legacy vs Laravel 12 Target

## Legacy Yii2 Architecture

```
controllers/              ← Business logic + request handling
  ├── SiteController.php
  ├── UserController.php
  └── ServiceController.php

models/                   ← ActiveRecord (data + validation + relations)
  ├── User.php        (rules(), relations, behaviors)
  ├── Service.php
  └── News.php

views/                    ← PHP + HTML mixed
  ├── site/
  ├── user/
  └── service/

config/                   ← Yii::$app config
  ├── main.php
  └── params.php

components/               ← Custom classes (widgets, helpers)
  └── MenuWidget.php
```

**Characteristics:**
- `ActiveRecord` handles DB, validation, relations, and partial business logic
- `Controllers` contain SQL queries, conditionals, and rendering
- `Yii::$app` is static global access
- Behaviors are coupled to model lifecycle
- No service layer — logic is in controllers or models

## Target Laravel 12 Architecture

```
app/
├── Domain/
│   ├── User/
│   │   ├── Models/User.php       ← Eloquent (data only)
│   │   ├── Actions/              ← Single-responsibility operations
│   │   ├── Services/             ← Business logic
│   │   ├── DTOs/                 ← Transfer objects
│   │   └── Http/
│   │       └── Requests/         ← FormRequest (validation only)
│   └── Service/
│       ├── Models/Service.php
│       ├── DTOs/ServiceData.php
│       └── Http/
│           └── Requests/

resources/views/
  ├── pages/                     ← Page templates
  └── components/
      ├── ui/                    ← Generic UI (card, button)
      ├── sections/              ← Feature sections
      └── layouts/               ← Layouts

routes/
  ├── web.php
  └── admin.php                  ← Filament routes
```

**Characteristics:**
- **Service layer** contains all business logic
- **Actions** are single-purpose classes
- **DTOs** are immutable data carriers — no Eloquent in views
- **FormRequest** handles validation (no rules in models)
- **Eloquent** models are thin (relations, casts, scopes)
- **Controllers** are thin — delegate to services/actions

## Migration Philosophy

### Extraction, Not Rewrite
- Never rewrite a Yii2 feature entirely.
- Extract logic from models into services/actions.
- Extract validation into FormRequests.
- Keep the same DB schema where possible.

### Incremental Transition
- Each commit must leave the app runnable.
- Yii2 and Laravel run side by side.
- A feature is migrated only when its Laravel route is live via Strangler Fig.

### Layer Isolation
- Controllers → thin, delegate
- Models → no business logic, only Eloquent definitions
- Services → all logic
- DTOs → data transfer, no behavior
