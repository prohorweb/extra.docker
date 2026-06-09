# AI Migration Layer — Entry Point (Yii2 → Laravel 12)

## 🎯 Purpose

This folder is not documentation and not an experiment.
It is a **working control system for migrating a legacy Yii2 application to Laravel 12**.
It exists to:

- preserve business logic during migration
- migrate the system feature-by-feature (not all at once)
- control changes through Git
- avoid accidental full rewrites
- maintain a clear view of the current migration state

---

## 🧠 Core Idea

We do NOT rewrite the system.
We perform a **gradual replacement of Yii2 with Laravel using the Strangler Fig approach**.

- Yii2 stays alive and operational
- Laravel gradually takes over specific features
- both systems coexist during migration

---

## 🧩 System Structure

The system is divided into four logical layers:

### 1. 📌 SYSTEM (rules and constraints)

File: `ai/SYSTEM.md`

Contains:
- core principles of AI behavior
- strict rules (minimal diffs, no refactoring)
- role definitions (Analyst / Executor)

👉 This is the "law of the system"

---

### 2. 📊 STATE (current status)

Files:
- `ai/CURRENT_STATE.md`
- `ai/TASKS.md`

Contains:
- what is already migrated
- what is in progress
- what is blocked
- next actionable steps

👉 This is the "control panel"

---

### 3. 🏗 ARCHITECTURE (target design)

File: `ai/ARCHITECTURE.md`

Contains:
- current Yii2 architecture
- target Laravel 12 architecture
- mapping between them

👉 This is the "blueprint of the future system"

---

### 4. 🧠 DOMAINS (feature-based decomposition)

Folder: `ai/domains/`

Examples:
- user.md
- service.md
- news.md

Contains:
- analysis of each business domain
- dependencies
- migration plan per feature

👉 This is the "system broken into parts"

---

## ⚙️ Migration Workflow (simple view)

### Step 1 — Analysis
Analyze Yii2 codebase → written into: `ai/domains/*.md`

### Step 2 — Planning
Define migration tasks → written into: `ai/TASKS.md`

### Step 3 — Execution
Implement Laravel replacement incrementally → code goes to: `app/Domain/*`

### Step 4 — Strangler Fig routing
- Yii2 remains active
- Laravel takes over specific routes gradually

### Step 5 — State update
After each change, update:
- `ai/CURRENT_STATE.md`
- `ai/TASKS.md`
- `ai/domains/*.md`

---

## 🧱 Core Rules

### ❌ Never do:
- full system rewrites
- deleting Yii2 code before Laravel replacement exists
- global refactoring across the project
- architectural redesign during migration
- multiple unrelated changes in one step

### ✅ Allowed:
- migrate feature by feature
- make minimal, incremental changes
- keep Yii2 running during entire process
- introduce Laravel side-by-side
- document every decision

---

## 🧠 Mental Model

Think of the system like this:
- Yii2 = existing working engine
- Laravel = new engine being installed gradually
- AI = assistant moving parts between systems
- Git = single source of truth
- human = final controller

---

## 🧭 How to work with this system

Before any task:
1. Open `ai/CURRENT_STATE.md`
2. Identify current domain
3. Check `ai/TASKS.md`
4. Select a single task
5. Execute only that scope

---

## 🔥 Important Principle

This system does NOT magically accelerate development.
It provides something more important:
> **predictability and safety during legacy migration**

---

## 🧩 Summary

- `ai/` = migration brain
- Yii2 = current production system
- Laravel = target system
- Git = history and truth
- human = system controller

---

## 📌 Status

This system is:
- actively evolving
- not final
- but governed by stable migration principles