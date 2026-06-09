# Migration Rules — Strict Constraints

## Incremental Changes Only

1. **One concern per commit.** Do not mix migration, refactoring, and formatting.
2. **Maximum 3 files per commit** unless strictly necessary.
3. **Each commit must compile and run** (tests pass or at least no syntax errors).

## System Must Remain Runnable

1. Never delete Yii2 code before Laravel replacement is live.
2. Never rename tables or columns while both apps access the DB.
3. Never remove a Yii2 route unless the Laravel equivalent is deployed via Strangler Fig.

## Preserve Behavior

1. Business logic must produce identical output for identical input.
2. Validation rules must be as strict as or stricter than the original.
3. Edge cases (null values, empty arrays, special characters) must be handled identically.

## No Large-Scale Refactors

1. Do not restructure the Yii2 codebase.
2. Do not rename Yii2 files or classes.
3. Do not introduce design patterns unless explicitly required.

## Git Diffs Must Be Reviewable

1. No auto-formatting (PSR-12, PHP CS Fixer, Prettier).
2. No whitespace changes in unmodified lines.
3. No sorting of imports or properties.
4. If a rename is necessary, do it in a separate commit.

---

## Yii2 → Laravel Mapping Rules

| Yii2 Construct | Laravel Equivalent | Notes |
|----------------|-------------------|-------|
| `CActiveRecord` | `Eloquent\Model` | Keep same table names |
| `rules()` | `FormRequest` or `Validator::make()` | No rules in models |
| `behaviors` (TimestampBehavior) | Eloquent events/traits (`booted()`, `HasTimestamps`) | |
| `behaviors` (BlameableBehavior) | Middleware or service injection | |
| `relations()` | Eloquent `belongsTo()`, `hasMany()` etc. | Keep same relation names |
| `Yii::$app->user->id` | `auth()->id()` or request injection | |
| `Yii::$app->request` | `$request` injection | |
| `Yii::$app->db` | `DB::` facade or `Model::query()` | |
| `CActiveDataProvider` | `LengthAwarePaginator` | |
| `Yii::t()` | `__()` helper | Keep translation files |
| `widgets` | Blade components `x-*` | |
| `CUploadedFile` | `$request->file()` | |
| `Yii::$app->mailer` | Laravel Mail (`Mail::send()`) | |
| `Yii::$app->cache` | `Cache::` facade | |
| Custom helpers | Services or facades | |