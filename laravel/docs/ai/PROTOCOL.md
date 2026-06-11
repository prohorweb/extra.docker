# AI Operational Protocol — Migration Layer

## 1. Session Initialization

**Read order:**
1. `SYSTEM.md` — core rules
2. `CURRENT_STATE.md` — migration status
3. `MIGRATION_RULES.md` — constraints
4. This file — communication protocol

**Identify role from request:**
- ANALYST: explain, analyze, map, audit, plan
- EXECUTOR: implement, create, migrate, update

## 2. Response Format

```yaml
mode: [ANALYST | EXECUTOR]
domain: [affected domain]
action: [what was done / what is proposed]
files_affected: [list]
risk_level: [LOW | MEDIUM | HIGH | CRITICAL]
```

**Analyst:**
- Analysis of [file/domain]:
  - Legacy structure: ...
  - Dependencies: ...
  - Risks: ...
  - Recommendation: ...
  - Effort: [S|M|L|XL]

**Executor:**
- Executing: [task]
- Files modified: [list]
- Changes made:
  - ...
- Verification: ...
- Next: ...

## 3. Decision Protocol

### Ask for clarification when:
- Ambiguous request without scope
- Request violates MIGRATION_RULES.md
- File referenced doesn't exist
- HIGH/CRITICAL risk proposed
- Scope creep detected

### Abort execution when:
- Would break running system
- >3 files modified without approval
- Deletes Yii2 before Laravel replacement is live
- Requires DB schema change without explicit approval
- Missing required package

## 4. Memory Update Protocol

**After analysis:**
- Update `domains/[domain].md` with findings
- Update `TASKS.md` (new tasks, dependencies)
- Update `CURRENT_STATE.md` if risks/blockers identified

**After execution:**
- Update `domains/[domain].md` (migration status)
- Update `TASKS.md` (mark DONE or progress %)
- Update `CURRENT_STATE.md` (new state, next steps)
- Add entry to `DECISION_LOG.md` if architectural decision made

## 5. Error Recovery

**If AI makes a mistake:**
1. Acknowledge immediately
2. Revert: provide git revert command
3. Document in `DECISION_LOG.md` under "Mistakes & Lessons"
4. Propose corrected approach

**If human disagrees:**
1. Acknowledge disagreement
2. Provide reasoning for original suggestion
3. Defer to human decision
4. Flag risk if contradicts MIGRATION_RULES.md

## 6. Session Termination

```yaml
Session summary:
- Mode: [ANALYST | EXECUTOR]
- Tasks completed: [list]
- Files modified: [list]
- Memory updated: [list]
- Next session should: ...
- Open questions: [list if any]
```

## 7. Prohibited Patterns

```yaml
never:
- "I think we should rewrite this entirely" → violates MIGRATION_RULES.md
- "Let me refactor this while I'm here" → scope creep
- "This is similar to X, so I'll apply the same pattern" → assumption without analysis
- "I'll fix formatting too" → violates diff reviewability
- "I'll delete old code since it's not needed" → violates Strangler Fig
```

## 8. Version

```yaml
protocol_version: 1.0.0
last_updated: June 2026
status: ACTIVE
owner: @lead-dev
```
