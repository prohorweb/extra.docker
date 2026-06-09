# Context: Laravel 12 Target System

## Overview

The target system is **Laravel 12** — a modern PHP framework with a service-oriented architecture.

## Key Characteristics

### 1. Service-Based Architecture
- Business logic lives in **Services** (e.g., `UserService`, `ServiceService`)
- Controllers are thin — they only receive requests, delegate to services, and return responses
- Models (Eloquent) contain only data definitions: relations, casts, scopes

### 2. Actions for Single Responsibility
- Each operation is a single class: `RegisterUserAction`, `UpdateProfileAction`
- Actions are testable, reusable, and independent
- No logic in controllers or models

### 3. DTOs for Data Transfer
- Views receive **Data Transfer Objects**, never Eloquent models
- DTOs are immutable, explicit, and decoupled from the database
- Prevents N+1 problems and lazy loading in views

### 4. FormRequest for Validation
```php
class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8'],
        ];
    }
}
```
- Validation is separated from models and controllers
- Rules are explicit and testable

### 5. Eloquent Models Are Thin
```php
class User extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime'];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
```
- No business logic
- No validation rules
- Only relations, casts, scopes, and attributes

### 6. Dependency Injection
- No static facades in business logic (if possible)
- Services receive dependencies via constructor
- Controllers receive services via type-hinting

### 7. Blade Components
```blade
<x-ui.card :title="$service->title">
    <x-slot:content>
        {{ $service->description }}
    </x-slot>
</x-ui.card>
```
- Reusable, self-contained UI components
- No PHP logic in views
- Attributes and slots for customization

## Available Laravel 12 Features

| Feature | Purpose |
|---------|---------|
| **Eloquent ORM** | Database access with ActiveRecord pattern |
| **Blade Templating** | View engine with components and slots |
| **Laravel Mail** | Email sending with Markdown templates |
| **Queue (Redis)** | Async job processing |
| **Cache (Redis/File)** | Data caching |
| **Laravel Sanctum** | API authentication |
| **Laravel Pennant** | Feature flags |
| **Laravel Pulse** | Application monitoring |
| **Laravel Horizon** | Queue management |
| **Pest PHP** | Testing framework |
| **Laravel Dusk** | E2E browser testing |
| **FilamentPHP v3** | Admin panel |
| **Spatie Media Library** | File uploads and media management |

## Migration Advantages

1. **Clear separation of concerns** — Services, Actions, DTOs, FormRequests
2. **Explicit dependencies** — Constructor injection replaces static calls
3. **Testable architecture** — Every component can be tested in isolation
4. **View-layer safety** — DTOs prevent accidental DB queries in templates
5. **Incremental adoption** — Strangler Fig pattern allows gradual migration