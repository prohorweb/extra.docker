# Domain: User

## Migration Status: 🟡 IN PROGRESS

---

## Legacy Yii2 Files

| File | Role | Coupling |
|------|------|----------|
| `common/models/User.php` | ActiveRecord (data + validation + behaviors) | HIGH — `TimestampBehavior`, `BlameableBehavior` |
| `controllers/UserController.php` | CRUD + business logic | HIGH — SQL queries, `Yii::$app->user`, session |
| `views/user/` | Profile, settings, account pages | MEDIUM — mixed PHP/HTML |
| `views/user/_form.php` | Form partial | LOW — extracted view |
| `controllers/SiteController.php` (login/register) | Authentication | HIGH — uses `User` model directly |

---

## Target Laravel 12 Structure

```
app/Domain/User/
├── Models/
│   └── User.php                   ← Eloquent (relations, casts, scopes)
├── Actions/
│   ├── RegisterUserAction.php     ← Handles registration flow
│   ├── UpdateProfileAction.php    ← Updates user profile
│   └── ChangePasswordAction.php   ← Password change with validation
├── Services/
│   ├── UserService.php            ← Business logic (queries, filters)
│   └── AuthService.php            ← Authentication logic
├── DTOs/
│   ├── UserData.php               ← Public user data (no password)
│   └── ProfileData.php            ← Profile update transfer object
├── Http/
│   ├── Controllers/
│   │   └── UserController.php     ← Thin — delegates to actions/services
│   └── Requests/
│       ├── RegisterRequest.php    ← Validation rules
│       ├── UpdateProfileRequest.php
│       └── ChangePasswordRequest.php
├── Events/
│   ├── UserRegistered.php         ← Fires after registration
│   └── PasswordChanged.php
└── Notifications/
    └── WelcomeNotification.php    ← Mail notification
```

---

## Dependencies

| Dependency | Domain | Type |
|------------|--------|------|
| `Service` (services) | Service | User reads Service model via relation |
| `News` (articles) | News | User hasMany News (author) |
| `Order` (purchases) | Order | User hasMany Order |
| `Profile` (extended info) | Profile | User hasOne Profile |

---

## Behaviors Mapping

| Yii2 Behavior | Eloquent Equivalent | Status |
|---------------|--------------------|--------|
| `TimestampBehavior` | `HasTimestamps` trait | ⏳ PENDING |
| `BlameableBehavior` | Middleware + service injection | ⏳ PENDING |
| `SoftDeleteBehavior` | `SoftDeletes` trait | ⏳ PENDING |