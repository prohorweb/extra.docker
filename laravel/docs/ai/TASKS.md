# Tasks

## Last updated: June 9, 2026

## Completed
- [x] Consolidated two separate `ai/` documentation systems into `laravel/docs/ai/`
- [x] Fully rebuilt Map component using Tailwind v4 and Blade Components
- [x] Cleaned and standardized all core migration documentation in English
## In Progress
- [ ] Migration of the Homepage (`frontend/views/site/index.php` → Laravel)

## Planned — Next 3 Days

### June 10, 2026 (Day 1 — Analysis & Foundation)
- [ ] Deep analysis of `frontend/views/site/index.php` (all sections, variables, logic, and JavaScript)
- [ ] Update `domains/home.md` with detailed component plan
- [ ] Create `HomeController` and base route for `/`

### June 11, 2026 (Day 2 — Component Development)
- [ ] Create key Blade components:
  - `x-layout.navigation`
  - `x-sections.hero` (with video + banner support)
  - `x-sections.actions` (promotions)
  - `x-sections.subscribe`
- [ ] Connect data from Laravel models (`Club`, `ClubBanner`, `Share`, `Metro`, `Settings`)

### June 12, 2026 (Day 3 — Integration)
- [ ] Assemble complete `home.blade.php` from new components
- [ ] Implement Strangler Fig routing (gradual replacement of Yii2 homepage)
- [ ] Test and refine the new homepage
- [ ] Update documentation

## Rules
- Work section by section
- Keep changes small, incremental, and reversible
- Always update `CURRENT_STATE.md` and `TASKS.md` after completing work
- Read `CURRENT_STATE.md` and relevant domain file before starting a task

**Focus**: Complete a clean, maintainable migration of the homepage.

