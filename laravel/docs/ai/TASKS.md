# Current Engineering Tasks

## Domain Analysis (Analyst)

| ID | Task | Status | Priority |
|----|------|--------|----------|
| DA-01 | Audit Home feature: controllers, views, models, routes | ✅ DONE | HIGH |
| DA-02 | Map Yii2 `User` behaviors to Eloquent equivalents | 🟡 IN PROGRESS | HIGH |
| DA-03 | Identify all `Yii::$app->user->id` usages in controllers | 🔴 BLOCKED | MEDIUM |
| DA-04 | Audit Service feature (dependencies, DB queries) | ⏳ PENDING | MEDIUM |
| DA-05 | Audit News feature (relations, SEO structure) | ⏳ PENDING | LOW |

## Migration Steps (Executor)

| ID | Task | Status | Dependencies |
|----|------|--------|--------------|
| MS-01 | Create `HomepageData` DTO | ✅ DONE | DA-01 |
| MS-02 | Create Blade components (`x-ui.hero`, `x-ui.card`) | ✅ DONE | DA-01 |
| MS-03 | Create `User` Eloquent model with mapped behaviors | 🟡 IN PROGRESS | DA-02 |
| MS-04 | Migrate HomeController to Laravel | ⏳ PENDING | MS-01, MS-02, MS-03 |
| MS-05 | Create SEO data integration for Home | ⏳ PENDING | MS-04 |
| MS-06 | Deploy Home via Strangler Fig | ⏳ PENDING | MS-05 |

## Pending Refactors (Non-Blocking)

| ID | Refactor | Impact | Status |
|----|----------|--------|--------|
| RF-01 | Extract validation from `Service.rules()` to FormRequest | LOW | ⏳ PENDING |
| RF-02 | Replace `CActiveDataProvider` with Laravel paginator | MEDIUM | ⏳ PENDING |
| RF-03 | Replace Yii2 widgets with Blade components (top menu) | LOW | ⏳ PENDING |

## Blocked Areas

| Area | Blocking | Reason |
|------|----------|--------|
| User migrations (Phase 1) | MS-03 | Waiting on behavior mapping analysis (DA-02) |
| Service feature (Phase 2) | DA-04 | Not yet audited |