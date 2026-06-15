# Правила миграции — Жёсткие ограничения

## Инкрементальность

1. **Один контекст на коммит.** Нельзя смешивать миграцию, рефакторинг и форматирование.
2. **После каждого коммита система должна компилироваться и работать.**

## Система остаётся рабочей

1. Не удалять Yii2-код до тех пор, пока Laravel-замена не развёрнута.
2. Не переименовывать таблицы или колонки, пока обе системы читают БД.
3. Не удалять Yii2-маршрут, пока Laravel-эквивалент не запущен через Strangler Fig.

## Сохранение поведения

1. Бизнес-логика должна давать идентичный результат для одинаковых входных данных.
2. Правила валидации должны быть такими же строгими или строже оригинала.
3. Крайние случаи (null, пустые массивы, спецсимволы) обрабатываются идентично.

## Без масштабного рефакторинга

1. Не реструктурировать кодовую базу Yii2.
2. Не переименовывать файлы или классы Yii2.
3. Не вводить паттерны, которые не требуются явно для задачи.

## Читаемость диффа

1. Без авто-форматирования (PSR-12, PHP CS Fixer, Prettier).
2. Без изменений пробелов в нетронутых строках.
3. Без сортировки импортов или свойств.
4. Переименование — отдельный коммит.

---

## Маппинг Yii2 → Laravel

| Yii2 | Laravel | Примечание |
|------|---------|------------|
| `CActiveRecord` | `Eloquent\Model` | Сохраняем имена таблиц |
| `rules()` | `FormRequest` или `Validator::make()` | Правила не в моделях |
| `TimestampBehavior` | `HasTimestamps` (встроен в Eloquent) | |
| `BlameableBehavior` | Middleware или инъекция сервиса | |
| `SoftDeleteBehavior` | `SoftDeletes` trait | |
| `relations()` | `belongsTo()`, `hasMany()` и т.д. | Сохраняем имена отношений |
| `Yii::$app->user->id` | `auth()->id()` | |
| `Yii::$app->request` | Инъекция `$request` | |
| `Yii::$app->db` | Фасад `DB::` или `Model::query()` | |
| `CActiveDataProvider` | `LengthAwarePaginator` | |
| `Yii::t()` | Хелпер `__()` | Сохраняем файлы переводов |
| `widgets` | Blade-компоненты `x-*` | |
| `CUploadedFile` | `$request->file()` | |
| `Yii::$app->mailer` | `Mail::send()` через очередь | |
| `Yii::$app->cache` | Фасад `Cache::` | |
| Кастомные хелперы | Services или Facades | |

---

## Правила слоя данных (Data Layer)

| Слой | Ответственность | Нельзя |
|------|----------------|--------|
| `Eloquent Model` | Отношения, касты, скоупы, атрибуты | Бизнес-логика, валидация |
| `DTO (readonly class)` | Перенос данных из модели во вьюху | Любая логика, Eloquent-запросы |
| `Service` | Агрегация данных, бизнес-логика | HTTP-запросы, рендеринг |
| `FormRequest` | Валидация входящих данных | Бизнес-логика |
| `Controller` | Принять запрос → вызвать сервис → вернуть вьюху | SQL-запросы, логика |
| `Blade` | Только отображение DTO | Eloquent, бизнес-логика, JS-логика |

---

## Правила Git

```
Формат коммита: migrate([домен]): [описание]

Примеры:
  migrate(home): add HeroDTO and x-sections.hero component
  migrate(services): create ServiceService with getPaginated()
  fix(home): correct hero slider mobile breakpoint
  docs(ai): update CURRENT_STATE after homepage completion
```

---

## Структура домена (целевая)

```
app/
├── Models/          ← Eloquent (тонкие: отношения, касты, скоупы)
├── Data/            ← DTOs (readonly class, fromModel, collection)
│   ├── Home/
│   ├── Services/
│   ├── News/
│   └── SEO/
├── Services/        ← Бизнес-логика (stateless, injectable)
└── Http/
    ├── Controllers/ ← Тонкие (принять → делегировать → вернуть)
    └── Requests/    ← Валидация (FormRequest)

resources/views/
├── pages/           ← Страницы (получают только DTO)
└── components/
    ├── ui/          ← Универсальные компоненты (button, card, input)
    ├── sections/    ← Секции страниц (hero, actions, contacts)
    └── layouts/     ← Layouts, header, footer, nav
```
