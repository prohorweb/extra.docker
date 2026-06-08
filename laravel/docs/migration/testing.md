"# Testing Strategy

---

## Stack

| Layer | Tool | Purpose |
|-------|------|---------|
| **Unit Tests** | Pest PHP | DTO, Service, Helper functions |
| **Feature Tests** | Pest PHP + Laravel | Controllers, Forms, Filament Resources |
| **Component Tests** | Pest PHP + Laravel | Blade components rendering |
| **E2E Tests** | Laravel Dusk (future) | Critical user flows |
| **Performance** | Lighthouse CI | Core Web Vitals |

---

## Test Directory Structure

```
tests/
├── Feature/
│   ├── Controllers/
│   │   ├── Web/
│   │   │   ├── HomeControllerTest.php
│   │   │   ├── ServiceControllerTest.php
│   │   │   └── ...
│   │   └── Api/
│   │       ├── CallbackControllerTest.php
│   │       └── ContactControllerTest.php
│   ├── Forms/
│   │   ├── CallbackRequestTest.php
│   │   └── ContactRequestTest.php
│   ├── Filament/
│   │   ├── CallbackResourceTest.php
│   │   ├── ServiceResourceTest.php
│   │   └── ...
│   └── Mail/
│       ├── CallbackNotificationTest.php
│       └── ContactNotificationTest.php
├── Unit/
│   ├── Data/
│   │   ├── HomepageDataTest.php
│   │   ├── ServiceCardDataTest.php
│   │   └── ...
│   ├── Services/
│   │   ├── HomepageServiceTest.php
│   │   └── ServiceServiceTest.php
│   └── Support/
│       └── HelpersTest.php
└── Browser/ (future)
    └── HomepageTest.php
```

---

## Writing Tests: Examples

### Feature Test: Homepage

```php
<?php

test('homepage returns 200', function () {
    $response = $this->get(route('home'));

    $response->assertStatus(200);
});

test('homepage contains required sections', function () {
    $response = $this->get(route('home'));

    $response->assertSee('ТВОЁ ТЕЛО');
    $response->assertSee(route('club'));
    $response->assertSee('Записаться');
});

test('homepage has correct SEO meta tags', function () {
    $response = $this->get(route('home'));

    $response->assertSee('<title>Extra Fitness', false);
    $response->assertSee('og:title', false);
    $response->assertSee('twitter:card', false);
    $response->assertSee('schema.org', false);
});

test('homepage loads sections without errors', function () {
    $response = $this->get(route('home'));

    $response->assertDontSee('Undefined variable');
    $response->assertDontSee('Call to undefined function');
    $response->assertDontSee('\\nException');
});
```

### Feature Test: Callback Form

```php
<?php

use App\\Models\\Club;
use Illuminate\\Support\\Facades\\Mail;
use App\\Mail\\CallbackNotification;

test('callback form requires all fields', function () {
    $response = $this->postJson('/api/callback', []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['name', 'phone', 'club_id', 'consent']);
});

test('callback form validates phone format', function () {
    $response = $this->postJson('/api/callback', [
        'name' => 'Иван Иванов',
        'phone' => '12345', // invalid format
        'club_id' => 1,
        'consent' => true,
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrorFor('phone');
});

test('callback form submits successfully', function () {
    Mail::fake();

    $club = Club::factory()->create();

    $response = $this->postJson('/api/callback', [
        'name' => 'Иван Иванов',
        'phone' => '+7 (999) 123-45-67',
        'club_id' => $club->id,
        'consent' => true,
        'honeypot' => '',
    ]);

    $response->assertStatus(201);
    $response->assertJson(['success' => true]);

    $this->assertDatabaseHas('callbacks', [
        'name' => 'Иван Иванов',
        'phone' => '+7 (999) 123-45-67',
    ]);

    Mail::assertQueued(CallbackNotification::class);
});

test('callback form is rate limited', function () {
    $club = Club::factory()->create();

    // Submit 4 times (limit is 3 per minute)
    for ($i = 0; $i < 3; $i++) {
        $this->withHeaders(['X-Forwarded-For' => '192.168.1.1'])
            ->postJson('/api/callback', [
                'name' => 'Иван',
                'phone' => '+7 (999) 123-45-67',
                'club_id' => $club->id,
                'consent' => true,
            ]);
    }

    // 4th request should be rate limited
    $response = $this->withHeaders(['X-Forwarded-For' => '192.168.1.1'])
        ->postJson('/api/callback', [
            'name' => 'Иван',
            'phone' => '+7 (999) 123-45-67',
            'club_id' => $club->id,
            'consent' => true,
        ]);

    $response->assertStatus(429);
});

test('callback form rejects honeypot filled', function () {
    $club = Club::factory()->create();

    $response = $this->postJson('/api/callback', [
        'name' => 'Бот',
        'phone' => '+7 (999) 123-45-67',
        'club_id' => $club->id,
        'consent' => true,
        'honeypot' => 'I am a bot',
    ]);

    $response->assertStatus(422);
});
```

### Unit Test: DTO

```php
<?php

use App\\Data\\Home\\HomepageData;

test('HomepageData contains all required fields', function () {
    // Arrange
    Club::factory()->create();
    Service::factory()->featured()->count(6)->create();
    Share::factory()->featured()->count(3)->create();
    Trainer::factory()->featured()->count(4)->create();

    // Act
    $data = HomepageData::fromRequest();

    // Assert
    expect($data)->toBeInstanceOf(HomepageData::class);
    expect($data->hero)->toBeInstanceOf(HeroData::class);
    expect($data->services)->toHaveCount(6);
    expect($data->shares)->toHaveCount(3);
    expect($data->trainers)->toHaveCount(4);
    expect($data->club)->toBeInstanceOf(Club::class);
});

test('ServiceCardData converts model correctly', function () {
    // Arrange
    $service = Service::factory()
        ->withCategory()
        ->withTags()
        ->create([
            'title' => 'Индивидуальная тренировка',
            'price_from' => 2500,
        ]);

    // Act
    $data = ServiceCardData::fromModel($service);

    // Assert
    expect($data->id)->toBe($service->id);
    expect($data->title)->toBe('Индивидуальная тренировка');
    expect($data->priceFrom)->toContain('2 500');
    expect($data->priceFrom)->toContain('₽');
    expect($data->categoryName)->not->toBeNull();
    expect($data->tags)->toBeArray();
});
```

### Filament Resource Test

```php
<?php

use App\\Filament\\Resources\\CallbackResource;
use App\\Models\\Callback;
use Filament\\Tables\\Actions\\ViewAction;

test('CallbackResource lists all records', function () {
    Callback::factory()->count(5)->create();

    $response = $this->get(CallbackResource::getUrl('index'));

    $response->assertSuccessful();
    $response->assertSee('Имя');
    $response->assertSee('Телефон');
});

test('CallbackResource can mark as processed', function () {
    $callbacks = Callback::factory()->count(3)->create(['processed' => false]);

    $this->post(CallbackResource::getUrl('index'), [
        'records' => [$callbacks[0]->id],
        'action' => 'markProcessed',
    ]);

    expect($callbacks[0]->fresh()->processed)->toBeTrue();
    expect($callbacks[1]->fresh()->processed)->toBeFalse();
});
```

---

## CI Pipeline (GitHub Actions)

```yaml
name: Tests

on:
  pull_request:
    branches: [develop, main]

jobs:
  phpunit:
    runs-on: ubuntu-latest
    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_DATABASE: testing
          MYSQL_USER: user
          MYSQL_PASSWORD: password
          MYSQL_ROOT_PASSWORD: root
        ports: ['3306:3306']
        options: --health-cmd="mysqladmin ping" --health-interval=10s

    steps:
      - uses: actions/checkout@v4
      - uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
          extensions: pdo, pdo_mysql

      - run: cp .env.testing .env
      - run: composer install --no-interaction --prefer-dist
      - run: npm ci
      - run: npm run build

      - run: php artisan key:generate
      - run: php artisan migrate --env=testing

      - run: php artisan test
        env:
          DB_DATABASE: testing
          DB_USERNAME: user
          DB_PASSWORD: password

  lighthouse:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - name: Run Lighthouse CI
        uses: treosh/lighthouse-ci-action@v11
        with:
          urls: |
            https://extra.new/
          budgetPath: ./lighthouse-budget.json
          uploadArtifacts: true
          temporaryPublicStorage: true
```

---

## Lighthouse Budget

**File**: `lighthouse-budget.json`

```json
[
    {
        "categories": {
            "performance": {
                "score": 0.9
            },
            "accessibility": {
                "score": 0.95
            },
            "seo": {
                "score": 0.95
            },
            "best-practices": {
                "score": 0.9
            }
        },
        "timings": {
            "first-contentful-paint": 2000,
            "largest-contentful-paint": 3000,
            "total-blocking-time": 200,
            "cumulative-layout-shift": 0.1,
            "speed-index": 3000
        }
    }
]
```

---

## Running Tests

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --filter=Homepage
php artisan test --filter=Callback
php artisan test --filter=DTO

# Run with coverage
php artisan test --coverage --min=80

# Run Lighthouse
npx lhci autorun
```

---

## Navigation

- [← Roadmap](./roadmap.md)
- [Patterns →](./patterns.md)
- [Cookbook →](./cookbook.md)
- [Troubleshooting →](./troubleshooting.md)"