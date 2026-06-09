# AI Bootstrap — Migration System Entry Point (v2)

## Purpose

This file is the mandatory runtime entry point for any AI model operating in the Yii2 → Laravel 12 migration system.

It defines execution rules, context order, and safety constraints.

---

## SYSTEM INITIALIZATION ORDER (MANDATORY)

Before doing anything, ALWAYS read in this exact order:

1. SYSTEM_OVERVIEW.md (current state & priorities)
2. migration/README.md (planned strategy)
3. journal/SUMMARY.md (history & decisions)
4. ai/README.md (AI reasoning rules)
5. runtime/CONTEXT.md

## System Layers

This system consists of 3 conceptual layers:

1. Documentation Layer (docs/)
   - bootstrap.md
   - SYSTEM_OVERVIEW.md
   - migration/
   - journal/

2. AI Knowledge Layer (docs/ai/)
   - reasoning rules
   - domain models
   - interpretation guides

3. Runtime Layer (docs/runtime/)
   - execution rules
   - agent behavior constraints
   - task routing
   - state synchronization

---

## SOURCE OF TRUTH HIERARCHY

If any conflict exists:

1. journal/ → REAL SYSTEM STATE (ABSOLUTE TRUTH)
2. migration/ → INTENDED PLAN (FALLBACK)
3. ai/ → RULES & CONSTRAINTS (INTERPRETATION LAYER)

AI MUST NEVER:
- assume missing state
- override journal with assumptions
- treat migration plan as reality

---

## CORE MISSION

You are an AI assistant helping migrate a legacy Yii2 system into Laravel 12 using:

- Strangler Fig pattern
- Vertical Slice migration
- Incremental safe diffs
- Zero behavior change policy

---

## OPERATING MODES

### ANALYST MODE (READ ONLY)
Allowed:
- code analysis
- dependency mapping
- migration planning
- risk detection

Forbidden:
- any code changes

---

### EXECUTOR MODE (WRITE MODE)
Allowed:
- minimal code changes
- feature migration
- DTO / service creation
- Laravel implementation

Hard limits:
- max 3 files per change
- no global refactors
- no Yii2 modification unless explicitly required
- system must remain runnable after every change

---

## CRITICAL SAFETY RULES

AI MUST STOP IF:

- journal and migration plan conflict
- required context is missing
- change affects >3 files
- database schema modification is involved
- Yii2 code deletion is requested

In such cases:
→ ask clarification
→ do NOT guess or proceed

---

## MIGRATION PRINCIPLES

- Never rewrite full modules
- Always migrate vertically (feature-by-feature)
- Preserve behavior exactly
- Keep Yii2 and Laravel running in parallel
- Reduce diff size at all times

---

## OUTPUT EXPECTATIONS

AI responses must include:

- clear reasoning
- explicit risk level
- minimal change plan (if EXECUTOR)
- dependency awareness from journal state

---

## IMPORTANT RULE

This system is NOT documentation.

It is a live engineering control system.

Failure to follow journal state = incorrect behavior.

---

END OF BOOTSTRAP