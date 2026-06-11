# Project Documentation Overview — Yii2 → Laravel 12 Migration System

## Purpose of This Documentation

This folder (`docs/`) describes a **controlled, incremental migration system** for transforming a legacy Yii2 application into a Laravel 12 architecture using a Strangler Fig + Vertical Slice approach.

This is not just documentation — it is a **living engineering control system** that combines:

- migration strategy
- runtime state tracking
- AI operational rules
- domain decomposition
- execution discipline

It is designed to be used by both:
- human engineers
- AI coding agents (Continue, Aider, MLX, cloud LLMs)

---

## High-Level Goal

The goal of this project is:

> Safely migrate a production Yii2 monolith into a modular Laravel 12 system without breaking runtime behavior.

Key constraints:

- No full rewrites
- No system downtime
- No breaking changes to business logic
- Incremental feature-by-feature migration
- Yii2 and Laravel run side-by-side during transition

---

## System Architecture of the Documentation Layer

This documentation is split into 3 core layers:

### 1. AI Operational Layer (`docs/ai/`)

This layer defines **how AI should think and behave**.

It includes:

- `SYSTEM.md` → core rules, principles, constraints
- `PROTOCOL.md` → communication and execution rules for AI agents
- `RUNTIME.md` → execution pipeline and tool routing logic
- `MIGRATION_RULES.md` → strict migration constraints and mappings
- `ARCHITECTURE.md` → Yii2 vs Laravel structural comparison
- `TASKS.md` → structured migration task list
- `DECISIONS.md` → architectural decision log (ADR-style)
- `domains/*.md` → per-domain migration context (User, Service, etc.)

### Purpose:

Defines:
- how AI should reason
- what is allowed or forbidden
- how changes are executed safely
- how context is maintained across sessions

---

### 2. Migration Strategy Layer (`docs/migration/`)

This layer defines **what to do and in what order**.

It contains:

- roadmap of migration phases
- vertical slice decomposition plan
- feature prioritization
- dependency ordering between domains

### Key principle:

> Migrate full vertical features (e.g. Home, User, Service), not layers (controllers/models separately).

---

### 3. Runtime State Layer (`docs/sessions/`)

This layer reflects **the real current state of the system**.

It includes:

- `SUMMARY.md` → live snapshot of migration progress
- historical notes of completed work
- known issues and blockers
- production state awareness

### Purpose:

This is the **source of truth for reality**, not plans.

---

## Core Migration Philosophy

### 1. Strangler Fig Pattern

Yii2 is never removed immediately.

Instead:

- Laravel replaces parts of the system incrementally
- routes are gradually redirected
- both systems coexist during migration

---

### 2. Vertical Slice Migration

Each feature is migrated as a full unit:

Example:
- Home page → complete slice
- User system → complete slice
- Service module → complete slice

NOT:
- all models first
- then controllers
- then views

---

### 3. Minimal Diff Principle

Every change must:

- be small
- be reviewable
- preserve behavior exactly
- avoid architectural rewrites during migration

---

### 4. Behavior Preservation Rule

Yii2 logic must behave identically after migration.

This includes:

- validation rules
- business logic
- edge cases
- database behavior
- authorization rules

---

## AI Operating Model

When AI uses this documentation, it should follow this mental model:

### Step 1 — Understand Rules
Read `docs/ai/` to understand constraints and architecture.

### Step 2 — Understand Strategy
Read `docs/migration/` to understand priorities and roadmap.

### Step 3 — Understand Reality
Read `docs/sessions/SUMMARY.md` to understand current system state.

---

## AI Roles

### Analyst Mode
Used for:
- code understanding
- dependency mapping
- migration planning
- risk analysis

Output:
- explanations
- migration proposals
- task breakdowns

No code changes.

---

### Executor Mode
Used for:
- implementing migration steps
- creating Laravel equivalents
- applying minimal diffs

Rules:
- max 3 files per change
- no refactors beyond scope
- no Yii2 modifications unless explicitly required
- system must remain runnable

---

## Data Consistency Rule

Priority order of truth:

1. `sessions/` → actual system state (highest priority)
2. `migration/` → planned future state
3. `ai/` → rules and constraints (execution logic)

If conflict exists:

> sessions overrides everything except explicit human instruction

---

## How to Use This Documentation (IMPORTANT)

When giving this system to an AI model, instruct it:

> "Use this documentation as the single source of truth for reasoning about Yii2 → Laravel migration. Always follow the AI operational rules, respect migration order, and verify against sessions state before suggesting changes."

---

## Expected Output Quality from AI

When properly used, this system enables AI to:

- reason about legacy Yii2 structure safely
- propose incremental Laravel migrations
- avoid breaking production systems
- maintain strict diff discipline
- track progress across long sessions
- avoid hallucinating system state

---

## Summary

This documentation system is not a passive guide.

It is a **controlled cognitive framework for AI-assisted legacy system migration**.

It defines:
- how to think
- what to do
- what not to touch
- and how to stay aligned with reality during long-term transformation

---

---

## Bootstrap Integration

To activate this system in any AI model, always use:

→ docs/ai/bootstrap.md

This file is the execution entry point and must be provided together with this document.

Order of initialization:

1. bootstrap.md (execution rules)
2. AI_SYSTEM_OVERVIEW.md (system architecture)
3. migration/README.md (roadmap)
4. sessions/SUMMARY.md (current state)