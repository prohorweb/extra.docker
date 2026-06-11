# Current State — Yii2 → Laravel Migration

## Last updated: June 10, 2026

## Migration Status
**Project**: Yii2 → Laravel 12 (Strangler Fig Pattern)

### Completed
- [x] Map component fully migrated to Laravel + Tailwind v4
- [x] Consolidated documentation from two `ai/` folders into `laravel/docs/ai/`
- [x] Cleaned and standardized all core migration documentation in English
- [x] Nginx dual-host configuration: `extra.loc` (Yii2) + `extra.new` (Laravel)
- [x] Added Oswald & Roboto fonts (variable + all static weights)
- [x] Created domain specs: `home.md`, `map.md`

### In Progress
**Homepage Migration** (Component Development Phase)
- [ ] Deep analysis of `frontend/views/site/index.php` (all sections, variables, logic, and JavaScript)
- [ ] Update `domains/home.md` with detailed component plan
- [ ] Create `HomeController` and base route for `/`
- [ ] Build major Blade components: navigation, hero, actions, subscribe, metro, clubs
- [ ] Connect data from Eloquent models (`Club`, `ClubBanner`, `Share`, `Metro`, `Settings`)
- [ ] Assemble final `home.blade.php`
- [ ] Implement Strangler Fig routing (Yii2 → Laravel)
- [ ] Test and refine the new homepage

### Domain Status
| Domain     | Status       | Priority | Notes                          |
|------------|--------------|----------|--------------------------------|
| Map        | ✅ Completed | High     | Fully migrated to Laravel      |
| Home       | 🔄 In Progress | **High** | Components being built         |
| User       | Not Started  | Medium   | —                              |

---

## Process Health
| Metric                    | Target    | Status |
|---------------------------|-----------|--------|
| Divergence ai/* vs git    | 0%        | ✅     |
| Avg. commit size (files)  | ≤3        | ✅     |
| Domain files updated      | 100%      | ✅     |

---

*Last updated: June 10, 2026*
+++++++
REPLACE
