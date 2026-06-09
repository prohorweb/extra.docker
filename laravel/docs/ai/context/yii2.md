# Context: Yii2 Legacy System

## Overview

The existing system is built on **Yii2** — a PHP framework with a monolithic ActiveRecord architecture.

## Key Characteristics

### 1. ActiveRecord-Heavy Architecture
- Models (`CActiveRecord`) contain data mapping, validation (`rules()`), relations, and business logic
- Controllers directly call models and contain SQL queries, conditionals, and rendering
- No dedicated service layer

### 2. Controllers Contain Business Logic
```php
class UserController extends Controller
{
    public function actionIndex()
    {
        $query = User::find()->where(['status' => User::STATUS_ACTIVE]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
```

### 3. Static Global Access via Yii::$app
- `Yii::$app->user->id` — current user ID
- `Yii::$app->request` — HTTP request
- `Yii::$app->db` — database connection
- `Yii::$app->security` — password hashing, random strings
- `Yii::$app->params` — configuration values
- `Yii::$app->mailer` — email sending
- `Yii::$app->cache` — caching

### 4. Tight Coupling Between Layers
- Models know about validation rules (mixed with business logic)
- Controllers know about SQL queries
- Views often contain PHP logic directly
- Behaviors are coupled to model lifecycle events

### 5. Common Patterns
- `ActiveDataProvider` for paginated listing
- `CActiveForm` for form generation
- `widgets` for reusable UI components
- `behaviors` for cross-cutting concerns (timestamps, blameable, soft delete)
- `events` via `on()` and `trigger()` methods
- `Yii::t()` for translations

## Migration Challenges

1. **Behaviors** — No direct Laravel equivalent for all Yii2 behaviors
2. **Static Yii::$app calls** — Must be replaced with dependency injection
3. **Mixed logic** — Validation, business logic, and DB queries must be separated
4. **Translation** — Yii2 message files need conversion
5. **Widgets** — Must be rewritten as Blade components