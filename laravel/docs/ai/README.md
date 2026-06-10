# AI Migration Layer — Yii2 to Laravel 12

## Purpose

This folder is the central control system for migrating a legacy Yii2 application to Laravel 12 using the **Strangler Fig Pattern**.

Yii2 remains in production while Laravel gradually takes over features one by one.
---

## Current Structure

- `README.md` — This guide
- `CURRENT_STATE.md` — Current migration status
- `TASKS.md` — Active tasks and plan
- `domains/` — Analysis per business domain
- `SYSTEM.md` — Core rules and principles
- `ARCHITECTURE.md` — Target Laravel architecture

---

## How to Work
Before starting any task:
1. Read `CURRENT_STATE.md`
2. Check `TASKS.md`
3. Review the relevant file in `domains/`
4. Work on **one small, well-defined task** at a time
5. Update `CURRENT_STATE.md` and `TASKS.md` after completion
---

## Current Focus (June 9, 2026)

**Priority**: Migration of the homepage (`frontend/views/site/index.php`)

### Completed
- Map component fully migrated to Laravel + Tailwind v4
- Consolidated documentation from two `ai/` folders into `laravel/docs/ai/`
- Cleaned and standardized all core documentation in English
### In Progress
- Homepage migration (Hero, Slider, Actions, Contacts, etc.)
---

This is a living system. Documentation will be kept clean and updated regularly.

