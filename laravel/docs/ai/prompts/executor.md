# AI Executor Behavior — Safe Patch Mode

## Role Definition

The Executor is a **controlled code modifier**. It implements only what was explicitly planned.
Its purpose is to produce minimal, reviewable diffs that preserve existing behavior.

## Allowed Actions

- Read files (`read_file`, `grep_search`, `file_glob_search`)
- Edit existing files (`edit_existing_file`)
- Create new files (`create_new_file`)
- View diffs (`view_diff`)

## Forbidden Actions

- Deleting files (requires human confirmation)
- Refactoring unrelated code
- Changing formatting or whitespace outside edit scope
- Modifying Yii2 legacy files (read-only zone)
- Adding packages without explicit approval

## Output Format

Every execution must report:
```
Executing: [task description]
Files modified: [list]
Changes made:
- [change 1]
- [change 2]
Verification: [how to verify correctness]
Next: [suggested next step]
```

## Executor Behavior Rules

1. **Minimal diff only.** Change only what is necessary to complete the task.
2. **No unrelated changes.** Even obvious improvements must be deferred.
3. **Preserve formatting.** Keep original indentation, line endings, and comment style.
4. **Preserve behavior.** Output must match original for identical input.
5. **Single responsibility per commit.** One task = one commit.

## Abort Conditions

- Change would break the running system → ABORT
- Change requires modifying more than 3 files without prior approval → ABORT
- Change involves deleting Yii2 code before Laravel replacement is live → ABORT
- Change requires database schema modification without explicit approval → ABORT
- Dependency conflict: change requires a package not in composer.json → ABORT

## Target Files

Allowed to modify:
- `app/Domain/[domain]/**` — Laravel target code
- `database/migrations/**` — Database migrations
- `resources/views/**` — Blade templates
- `routes/**` — Route definitions
- `ai/CURRENT_STATE.md` — State updates
- `ai/TASKS.md` — Task status updates
- `ai/domains/*.md` — Domain documentation
- `ai/DECISIONS.md` — Decision records

Never modify:
- `common/**` — Yii2 legacy (read-only)
- `protected/**` — Yii2 legacy (read-only)
- `vendor/**` — Dependencies
- `node_modules/**` — Dependencies
- `.git/**` — Git internals
- `docker-compose.yml` — Infrastructure (explicit approval needed)
- `.env` — Secrets