# Current State — Yii2 → Laravel 12 Migration

## Infrastructure

| Component | Status | Notes |
|-----------|--------|-------|
| **Docker** | ✅ Running | `docker-compose.yml` with Yii2 and Laravel 12 containers |
| **Laravel 12** | 🟡 Active | Port 8080, Strangler Fig proxy active |
| **Yii2** | ✅ Active | Port 80, main production server |
| **Nginx** | ✅ Configured | Routes `/home`, `/services` → Laravel; rest → Yii2 |
| **Database** | ✅ Shared | Both apps connect to same MySQL 8 database |
| **Redis** | ✅ Active | Queue, cache (shared between apps) |

## Active Migration Focus

**Current domain:** Home (Phase 1 of 10)
**Status:** BLOCKED — waiting on `MenuData` DTO migration
**Next step:** Complete `HomepageData` SEO integration
**Human responsible:** @lead-dev

## Known Risks

| Risk | Severity | Mitigation |
|------|----------|------------|
| Yii2 ActiveRecord `behaviors` (TimestampBehavior, BlameableBehavior) | HIGH | Must be replaced with Eloquent events/traits |
| `Yii::$app->user->id` used in 43 controllers | HIGH | Needs middleware or service injection |
| `rules()` methods contain business logic mixed with validation | MEDIUM | Extract to FormRequest + Service |
| Yii2 `CActiveDataProvider` pagination in views | MEDIUM | Replace with Laravel paginator |
| Tight coupling between `Controller` and `ActiveRecord` in Yii2 | HIGH | Services layer not present |

## Current Workflow Steps

1. [x] Audit Home feature (controllers, views, models)
2. [x] Create `HomepageData` DTO
3. [x] Create Blade components (`x-ui.hero`, `x-ui.card`)
4. [ ] Map Yii2 `User` behaviors to Eloquent
5. [ ] Complete SEO data integration
6. [ ] Deploy Home slice via Strangler Fig
7. [ ] Validate with live traffic