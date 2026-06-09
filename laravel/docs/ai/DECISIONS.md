# Engineering Decisions — AI Migration Layer

---

## ADR-001: Local MLX Models for AI Assistance

**Decision:** Use local MLX models (e.g., Qwen 2.5, CodeQwen) instead of cloud APIs.

**Reason:**
- No data leakage of proprietary business logic.
- Predictable latency (no API rate limits).
- Full control over context window and behavior.
- Reproducible results across sessions.

---

## ADR-002: Single-Model Execution

**Decision:** One model per session. No mixture of specialists.

**Reason:**
- Reduces context fragmentation.
- More consistent behavior across analyst and executor roles.
- Simpler to maintain system prompts and memory files.

---

## ADR-003: Human-Controlled Migration

**Decision:** AI proposes → human approves and executes. No autonomous commits.

**Reason:**
- AI lacks understanding of business context and priorities.
- Yii2 → Laravel migration requires domain knowledge AI cannot acquire.
- Human maintains engineering responsibility for production systems.

---

## ADR-004: File-Based AI Memory (Not Vector DB)

**Decision:** Use markdown files in `ai/` folder instead of a vector database.

**Reason:**
- Zero infrastructure dependency.
- Files are human-readable and version-controlled.
- No embedding/similarity search overhead.
- Works with any AI model without integration.

---

## ADR-005: Vertical Slice over Horizontal Layer Migration

**Decision:** Migrate entire features (Home → Services → News) instead of layers (models → controllers → views).

**Reason:**
- Each slice is independently testable and deployable.
- Reduces risk of "dangling" half-migrated code.
- Strangler Fig can route entire feature to Laravel at once.
- Provides immediate user-facing value with each slice.

---

## ADR-006: DTO-First View Layer

**Decision:** Blade views receive only DTOs, never Eloquent models.

**Reason:**
- Prevents lazy loading access in views (N+1 problem).
- Enforces separation of concerns.
- Makes view data explicit and testable.
- Simplifies migration — view contract is just a DTO signature.