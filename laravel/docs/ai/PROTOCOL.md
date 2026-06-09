# AI Operational Protocol — Migration Layer

## Purpose

This file defines the **communication protocol** between human engineers and AI agents during the Yii2 → Laravel 12 migration. It is the binding contract that ensures consistent, predictable, and safe AI behavior across all sessions.

---

## 1. Session Initialization Protocol

Every AI session MUST begin with these steps:

### Step 1: Load Context

```yaml
Read order:
  1. ai/SYSTEM.md          # Core system prompt
  2. ai/CURRENT_STATE.md   # Current migration state
  3. ai/PROTOCOL.md        # This file — communication rules
  4. ai/MIGRATION_RULES.md # Migration constraints
```

### Step 2: Identify Role

```yaml
Determine role from user request:
  - "explain", "analyze", "map", "audit" → ANALYST mode
  - "implement", "create", "migrate", "update" → EXECUTOR mode
  - "plan", "propose", "design" → ANALYST mode (proposal only)
```

### Step 3: Acknowledge State

```yaml
Response template:
  "I am in [ANALYST|EXECUTOR] mode.
   Current focus: [domain from CURRENT_STATE.md]
   Last action: [last completed task]
   Next planned: [next task from TASKS.md]"
```

---

## 2. Communication Protocol

### 2.1 Response Format

All AI responses MUST follow this structure:

```yaml
mode: [ANALYST | EXECUTOR]
domain: [affected domain]
action: [what was done / what is proposed]
files_affected: [list of files]
risk_level: [LOW | MEDIUM | HIGH | CRITICAL]
```

### 2.2 Analyst Responses

```yaml
format:
  - "Analysis of [file/domain]:"
  - "  Legacy structure: [description]"
  - "  Dependencies: [list]"
  - "  Risks: [list]"
  - "  Recommendation: [proposed approach]"
  - "  Estimated effort: [S|M|L|XL]"
```

### 2.3 Executor Responses

```yaml
format:
  - "Executing: [task description]"
  - "Files modified: [list]"
  - "Changes made:"
  - "  - [change 1]"
  - "  - [change 2]"
  - "Verification: [how to verify correctness]"
  - "Next: [suggested next step]"
```

---

## 3. Decision Protocol

### 3.1 When to Ask for Clarification

AI MUST ask for clarification when:

```yaml
triggers:
  - Ambiguous request: "migrate this" without specifying scope
  - Conflicting rules: user request violates MIGRATION_RULES.md
  - Missing context: file referenced doesn't exist
  - Risk threshold: proposed change has HIGH or CRITICAL risk
  - Scope creep: request expands beyond original task
```

### 3.2 When to Abort

AI MUST abort execution and report to human when:

```yaml
abort_conditions:
  - Change would break the running system
  - Change requires modifying more than 3 files without prior approval
  - Change involves deleting Yii2 code before Laravel replacement is live
  - Change requires database schema modification without explicit approval
  - Dependency conflict: change requires a package not in composer.json
```

### 3.3 Escalation Path

```yaml
escalation:
  level_1: "Ask for clarification in current session"
  level_2: "Create entry in ai/DECISIONS.md for permanent record"
  level_3: "Flag to @lead-dev in ai/CURRENT_STATE.md"
```

---

## 4. Memory Update Protocol

After every significant action, AI MUST update memory files:

### 4.1 Update Rules

```yaml
after_analysis:
  - Update ai/domains/[domain].md with findings
  - Update ai/TASKS.md with new tasks or status changes
  - Update ai/CURRENT_STATE.md if risks or blockers identified

after_execution:
  - Update ai/domains/[domain].md with migration status
  - Update ai/TASKS.md (mark task as DONE or update progress)
  - Update ai/CURRENT_STATE.md (new state, next steps)
  - Add entry to ai/DECISIONS.md if architectural decision was made
```

### 4.2 Update Format

```yaml
update_template:
  "Memory update:
   - [file]: [change made]
   - [file]: [change made]
   Reason: [why this update is necessary]"
```

---

## 5. Error Recovery Protocol

### 5.1 If AI Makes a Mistake

```yaml
recovery_steps:
  1. Acknowledge the error immediately
  2. Revert the change (provide git revert command)
  3. Document the mistake in ai/DECISIONS.md under "Mistakes & Lessons"
  4. Propose corrected approach
```

### 5.2 If Human Disagrees with AI

```yaml
resolution:
  1. AI acknowledges disagreement
  2. AI provides reasoning for original suggestion
  3. AI defers to human decision
  4. If human decision contradicts MIGRATION_RULES.md, AI flags risk
```

---

## 6. Session Termination Protocol

Every session MUST end with:

```yaml
closing_template:
  "Session summary:
   - Mode: [ANALYST | EXECUTOR]
   - Tasks completed: [list]
   - Files modified: [list]
   - Memory updated: [list of updated files]
   - Next session should: [recommended starting point]
   - Open questions: [list if any]"
```

---

## 7. Prohibited Communication Patterns

```yaml
never:
  - "I think we should rewrite this entirely" → violates MIGRATION_RULES.md
  - "Let me refactor this while I'm here" → scope creep
  - "This is similar to X, so I'll apply the same pattern" → assumption without analysis
  - "I'll fix the formatting too" → violates diff reviewability
  - "I'll delete the old code since it's not needed" → violates Strangler Fig
```

---

## 8. Protocol Version

```yaml
protocol_version: 1.0.0
last_updated: 2025-01-XX
status: ACTIVE
owner: @lead-dev
```