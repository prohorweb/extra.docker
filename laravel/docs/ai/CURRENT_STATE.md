# Current State

## Last updated: June 9, 2026

## Migration Status

**Project**: Yii2 → Laravel 12 (Strangler Fig Pattern)

**Current Focus**: Migration of the homepage (`frontend/views/site/index.php` → Laravel `home.blade.php`)
### Completed
- Fully rebuilt the Map component (`map.blade.php`) using Tailwind v4 and Blade Components
- Consolidated two separate `ai/` documentation folders into `laravel/docs/ai/`
- Cleaned and standardized all core migration documentation in English
### In Progress
- **Homepage Migration** (Analysis & Planning Phase)
### Domain Status
| Domain     | Status       | Priority | Notes                          |
|------------|--------------|----------|--------------------------------|
| Map        | ✅ Completed | High     | Fully migrated to Laravel      |
| Home       | 🔄 In Progress | **High** | Most complex page on the site  |
| User       | Not Started  | Medium   | —                              |

### Next 3 Days Plan

**June 10 (Day 1)** — Analysis & Setup
- Deep analysis of `frontend/views/site/index.php`
- Create `HomeController` and base route
- Define component architecture (`navigation`, `hero`, `actions`, `subscribe`, etc.)

**June 11 (Day 2)** — Component Development
- Build major Blade components with Tailwind CSS
- Connect data from Eloquent models (`Club`, `ClubBanner`, `Share`, `Metro`)

**June 12 (Day 3)** — Integration
- Assemble final `home.blade.php`
- Implement Strangler Fig routing (Yii2 → Laravel)
- Testing and refinement
## Strategy
- Migrate **section by section**
- Keep changes small and reversible
- Maintain Yii2 as the live production system until Laravel fully replaces each feature

