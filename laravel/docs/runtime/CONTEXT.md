Ок — собираю тебе **`docs/runtime/CONTEXT.md v1`** как реальный execution engine, который будет “мозгом” всей системы.

---

# 📁 `docs/runtime/CONTEXT.md`

# AI Runtime Engine — Execution Layer (v1)

---

## 1. Purpose

This file defines the **runtime execution engine** for the Yii2 → Laravel 12 migration system.

It transforms AI from a “reasoning assistant” into a **controlled migration agent** with:

* deterministic execution flow
* strict tool routing
* state synchronization
* safe code modification rules

---

## 2. Core Principle

> The AI does not “decide freely”.
> It executes within a controlled lifecycle.

Every action MUST follow a structured pipeline:

```
INPUT → TASK PARSING → CONTEXT LOAD → MODE SELECTION → EXECUTION → STATE SYNC
```

---

## 3. TASK MODEL (MANDATORY ABSTRACTION)

Every request is converted into a TASK object.

```yaml
TASK:
  id: auto-generated
  type: ANALYZE | PLAN | EXECUTE | REVIEW

  domain: user | service | news | auth | etc.

  goal: "What must be achieved"

  files:
    - path: string
      role: legacy | target | memory

  constraints:
    max_files: 3
    allow_writes: true | false
    no_rewrite: true
    preserve_behavior: true

  dependencies:
    - TASK_ID or journal reference

  risk:
    level: LOW | MEDIUM | HIGH | CRITICAL
    reason: string
```

---

## 4. EXECUTION PIPELINE

### Step 1 — Context Loading

Always load in order:

1. `docs/bootstrap.md`
2. `docs/SYSTEM_OVERVIEW.md`
3. `docs/migration/README.md`
4. `docs/journal/SUMMARY.md`
5. `docs/ai/README.md`
6. domain context (if exists)

---

### Step 2 — TASK Interpretation

AI must determine:

* domain
* scope
* risk level
* required mode

If unclear → STOP and ask clarification.

---

### Step 3 — Mode Selection

| Mode     | Purpose                         |
| -------- | ------------------------------- |
| ANALYST  | read, analyze, map dependencies |
| EXECUTOR | modify code safely              |
| REVIEW   | validate changes                |

---

## 5. TOOL ROUTING

### ANALYST MODE (READ ONLY)

Allowed tools:

* file read
* grep / search
* dependency mapping

Forbidden:

* file writes
* code modification
* shell execution

Output:

* structured analysis
* migration plan
* risk report
* TASK proposals

---

### EXECUTOR MODE (WRITE CONTROLLED)

Allowed:

* create files
* edit files
* minimal diffs

Hard constraints:

* ❌ max 3 files per execution
* ❌ no global refactors
* ❌ no Yii2 deletion
* ❌ no DB schema changes
* ❌ no formatting-only commits

---

## 6. STATE SYNCHRONIZATION LOOP

After EVERY execution:

### Update order:

1. `docs/journal/SUMMARY.md`

   * reflect real system state

2. `docs/TASKS.md`

   * mark progress / DONE / BLOCKED

3. `docs/ai/domains/*.md`

   * update domain knowledge

4. `docs/DECISIONS.md` (if needed)

   * record architectural decisions

---

## 7. SOURCE OF TRUTH RULE

```
1. journal → REALITY (highest priority)
2. git → IMPLEMENTATION STATE
3. migration → INTENT
4. ai/ → INTERPRETATION
```

AI MUST NEVER:

* override journal with assumptions
* assume missing state
* treat migration plan as reality

---

## 8. SAFETY GUARDS (HARD STOPS)

Execution MUST STOP if:

* > 3 files affected
* database schema changes required
* Yii2 code deletion requested
* journal conflicts with migration plan
* missing context for decision making

On STOP:

→ request clarification
→ do NOT guess
→ do NOT proceed

---

## 9. MIGRATION RULES (ENFORCED)

* Vertical Slice only (feature-by-feature)
* Strangler Fig pattern required
* No full module rewrites
* Preserve behavior exactly
* Yii2 + Laravel must coexist
* Every step must be runnable

---

## 10. MEMORY MODEL

System memory is file-based:

* `journal/` → state
* `ai/domains/` → knowledge per domain
* `ai/README.md` → reasoning rules
* `migration/` → roadmap

No external memory is assumed.

---

## 11. FAILURE RECOVERY

If execution fails:

1. STOP immediately
2. Document what changed
3. Revert changes if needed
4. Log issue in `docs/DECISIONS.md`
5. Create recovery task in `docs/TASKS.md`

---

## 12. OUTPUT FORMAT (EXECUTOR)

```yaml
mode: EXECUTOR
domain: user
action: migration step
files_modified:
  - path
changes:
  - description
risk: LOW | MEDIUM | HIGH
verification:
  - how to validate
next_step:
  - suggestion
```

---

## 13. OUTPUT FORMAT (ANALYST)

```yaml
mode: ANALYST
domain: user
analysis:
  - findings
dependencies:
  - list
risks:
  - list
recommendation:
  - migration plan
next_task:
  - suggested TASK
```

---

## 14. SYSTEM INTENT

This runtime system ensures:

* predictable AI behavior
* safe incremental migration
* no architectural drift
* full control over legacy system transformation

---

## 15. FINAL RULE

> AI is not an architect.
> AI is a constrained execution engine for controlled system migration.

