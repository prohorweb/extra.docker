# AI Runtime Bridge — Execution Layer

## Purpose

This file defines the **execution layer** between AI tools (Continue, Aider, MLX) and the `ai/*.md` operational memory system. It converts declarative rules into executable behavior.

---

## 1. Runtime Architecture

```
User Request
     │
     ▼
┌─────────────────────┐
│  Input Pipeline     │  ← Converts request → TASK object
└─────────┬───────────┘
          │
          ▼
┌─────────────────────┐
│  Context Assembly   │  ← Loads SYSTEM.md + PROTOCOL.md + CURRENT_STATE.md
└─────────┬───────────┘
          │
          ▼
┌─────────────────────┐
│  Mode Router        │  ← ANALYST → read-only tools | EXECUTOR → write tools
└─────────┬───────────┘
          │
          ▼
┌─────────────────────┐
│  Execution          │  ← Constrained by TASK.files, TASK.constraints
└─────────┬───────────┘
          │
          ▼
┌─────────────────────┐
│  State Sync         │  ← Updates CURRENT_STATE.md + TASKS.md
└─────────────────────┘
```

---

## 2. Input Pipeline — TASK Specification

Every user request MUST be converted to a machine-readable TASK object before execution:

```yaml
TASK:
  id: "TASK-2025-01-XX-001"          # Auto-generated
  type: ANALYZE | EXECUTE | PLAN | REVIEW
  domain: "user"                      # From ai/domains/
  goal: "Map User behaviors to Eloquent equivalents"
  
  files:
    - path: "common/models/User.php"
      role: legacy
    - path: "app/Domain/User/Models/User.php"
      role: target
  
  constraints:
    max_files: 3
    allow_writes: false               # ANALYZE mode
    no_rewrite: true
    preserve_comments: true
    max_diff_lines: 50
  
  dependencies:
    - DA-02                           # From TASKS.md
  
  risk_assessment:
    level: MEDIUM
    reason: "Behaviors affect model lifecycle"
```

### TASK Type Definitions

| Type | Tools Allowed | File Writes | Output Format |
|------|---------------|-------------|---------------|
| `ANALYZE` | read, grep, search | `ai/*.md` only | Analysis in `ai/domains/*.md` |
| `EXECUTE` | read, write, edit | Migration code + `ai/*.md` | Git diff + state update |
| `PLAN` | read, search | `ai/TASKS.md` only | Task decomposition |
| `REVIEW` | read, diff | None | Review report |

---

## 3. Mode Router — Execution Routing

### ANALYST Mode

```yaml
route:
  tool: Continue | MLX (read-only)
  
  allowed_commands:
    - read_file
    - grep_search
    - file_glob_search
    - view_diff
    - ls
    
  forbidden_commands:
    - edit_existing_file
    - create_new_file
    - execute_shell
    - write_to_file
```

### EXECUTOR Mode

```yaml
route:
  tool: Aider | MLX (write-enabled)
  
  allowed_commands:
    - read_file
    - edit_existing_file
    - create_new_file
    - grep_search
    - view_diff
    
  restricted_commands:
    - execute_shell: "Only with explicit approval"
    - delete_file: "Never without human confirmation"
    
  file_scope:
    allowed:
      - "app/Domain/[domain]/**"
      - "database/migrations/**"
      - "resources/views/**"
      - "routes/**"
      - "ai/CURRENT_STATE.md"
      - "ai/TASKS.md"
      - "ai/domains/[domain].md"
      - "ai/DECISIONS.md"
    
    forbidden:
      - "vendor/**"
      - "node_modules/**"
      - ".git/**"
      - "common/**"                  # Yii2 legacy — read-only during migration
      - "protected/**"               # Yii2 legacy — read-only during migration
```

---

## 4. Execution Constraints — Hard Boundaries

### 4.1 File Write Authority

```yaml
write_authority:
  ALWAYS_ALLOWED:
    - ai/CURRENT_STATE.md
    - ai/TASKS.md
    - ai/domains/*.md
    - ai/DECISIONS.md
  
  EXECUTOR_ONLY:
    - app/Domain/**
    - database/migrations/**
    - resources/views/**
    - routes/**
  
  NEVER_FROM_AI:
    - common/**                      # Yii2 legacy (read-only)
    - protected/**                   # Yii2 legacy (read-only)
    - vendor/**
    - node_modules/**
    - .git/**
    - docker-compose.yml
    - .env
```

### 4.2 Diff Size Limits

```yaml
diff_limits:
  ANALYZE: null                      # No limit for analysis
  EXECUTE:
    max_files_per_commit: 3
    max_additions_per_commit: 100
    max_deletions_per_commit: 20
    max_changed_lines_per_file: 50
```

### 4.3 Execution Guards

```yaml
runtime_guards:
  - "If change involves >3 files → ABORT and request approval"
  - "If change modifies Yii2 code → ABORT (read-only zone)"
  - "If change deletes a file → ABORT (human confirmation required)"
  - "If change modifies database schema → FLAG as CRITICAL"
```

---

## 5. State Synchronization Protocol

### 5.1 After Every Action

```yaml
sync_rules:
  ANALYST:
    - Update ai/domains/[domain].md with findings
    - Update ai/TASKS.md (new tasks, dependencies discovered)
    - Update ai/CURRENT_STATE.md if new risks/blockers identified
  
  EXECUTOR:
    - Update ai/domains/[domain].md (migration_status)
    - Update ai/TASKS.md (mark task DONE or % complete)
    - Update ai/CURRENT_STATE.md (new state, next steps)
    - Commit with message format: "migrate([domain]): [description]"
```

### 5.2 Git as Source of Truth

```yaml
git_rules:
  - "Git is the ONLY source of truth for code state"
  - "ai/* files are DERIVED state — they describe what git contains"
  - "If git and ai/* conflict → git wins"
  - "Before any execution: run `git diff` to verify working tree is clean"
  - "After any execution: run `git diff` to verify only intended files changed"
```

---

## 6. Task Queue — Execution Pipeline

### 6.1 TASK Lifecycle

```yaml
states:
  PROPOSED:     "Identified but not yet planned"
  PLANNED:      "Added to TASKS.md with priority"
  IN_PROGRESS:  "Currently being executed"
  BLOCKED:      "Waiting on dependency or clarification"
  DONE:         "Completed and committed to git"
  ABORTED:      "Cancelled (decision recorded in DECISIONS.md)"
```

### 6.2 Queue Processing

```yaml
processing:
  1. "Read highest-priority PLANNED task from ai/TASKS.md"
  2. "Convert to TASK object (machine-readable)"
  3. "Load context (SYSTEM.md + PROTOCOL.md + CURRENT_STATE.md + domain)"
  4. "Route to ANALYST or EXECUTOR based on task type"
  5. "Execute within constraints"
  6. "Synchronize state"
  7. "Report completion / next task suggestion"
```

---

## 7. Error Recovery — Runtime

### 7.1 Execution Failure

```yaml
on_execution_failure:
  1. "Stop immediately"
  2. "Document what was changed (file list, git diff)"
  3. "Revert using: git checkout -- [affected files]"
  4. "Update ai/DECISIONS.md under 'Mistakes & Lessons'"
  5. "Create recovery TASK in ai/TASKS.md (status: PLANNED, priority: HIGH)"
  6. "Report to human: 'Aborted due to [reason]. Recovery task created.'"
```

### 7.2 State Divergence

```yaml
on_state_divergence:
  1. "Detect mismatch between ai/CURRENT_STATE.md and git"
  2. "Flag: 'State divergence detected'"
  3. "Suggest sync: 'Please run: git pull && git log --oneline -5'"
  4. "Do not execute until state is verified"
```

---

## 8. Runtime Version

```yaml
runtime_version: 1.0.0
last_updated: 2025-01-XX
status: ACTIVE
compatible_with:
  - Continue
  - Aider
  - MLX (local)
  - Claude Code (if adapted)
```

---

## Appendix A: Git Workflow Integration

```bash
# Standard execution cycle
git checkout -b migrate/[domain]   # Create branch
# AI executes TASK
git add -A
git commit -m "migrate([domain]): [description]"
git push origin migrate/[domain]
# PR created → human reviews → merges
```