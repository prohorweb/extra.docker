# Domain: База данных — extra_new

## Концепция
Чистая БД `extra_new` для Laravel. Yii2 продолжает работать с `extra`.
WordPress-подход: единая таблица `posts`, рубрики и клубы через `taxonomies`, SEO отдельно.
Клубы — тип таксономии. Контакты/настройки клуба — пост типа `setting`, привязан к клубу через `post_term`.
Роутинг по субдомену → middleware резолвит текущий клуб → все контроллеры получают `current_club`.

---

## Финальная схема (8 таблиц + системные)

### `taxonomies`
Рубрики, теги, специализации, клубы — всё в одной таблице.

| Поле | Тип | Примечание |
|------|-----|------------|
| id | bigint PK | |
| type | enum | **club**, category, specialization, department, tag |
| title | varchar | |
| slug | varchar | |
| position | int default 0 | |
| status | tinyint | 0=hidden, 10=active |
| parent_id | bigint nullable FK | для вложенных рубрик |

Клубы: `type=club`, slug = piter / matros / de-vision

---

### `posts`
Единая таблица всего контента сайта.

| Поле | Тип | Используется в type |
|------|-----|---------------------|
| id | bigint PK | все |
| type | enum | **setting**, news, service, share, event, job, trainer, card |
| status | tinyint | 0=draft, 10=active |
| position | int default 0 | все кроме news |
| title | varchar | все |
| slug | varchar nullable | все кроме setting |
| intro | text nullable | news, share, event |
| content | longtext nullable | все |
| img | varchar nullable | все |
| subtitle | varchar nullable | trainer (должность) |
| price | int nullable | share, card |
| date | date nullable | news, event |
| is_paid | boolean default false | event |
| is_open | boolean default true | event |
| is_banner | boolean default false | share → слайдер |
| banner_position | int nullable | share |
| banner_video | varchar nullable | share |
| banner_img_mobile | varchar nullable | share |
| published_at | timestamp nullable | |
| timestamps | | |

**type=setting** — данные клуба (1 пост на клуб):
поля через обычные колонки: `title` = название клуба, `content` = о клубе,
`img` = фото, плюс специфика через отдельные колонки:

| Доп. поля для setting | Тип |
|-----------------------|-----|
| tel | varchar nullable |
| email | varchar nullable |
| address | varchar nullable |
| coordinates | varchar nullable |
| working_hours | varchar nullable |
| working_hours_weekend | varchar nullable |

> Эти поля NULL для всех других типов. Альтернатива — `post_meta`, но для 6 полей проще колонки.

---

### `post_term` (pivot)
Связь поста с таксономией (клуб, рубрика, специализация).

| Поле | Тип |
|------|-----|
| post_id | bigint FK |
| taxonomy_id | bigint FK |

---

### `seo`
Полиморфная SEO-таблица.

| Поле | Тип | Примечание |
|------|-----|------------|
| id | bigint PK | |
| seoable_type | varchar | App\Models\Post |
| seoable_id | bigint | |
| meta_title | varchar nullable | |
| meta_description | text nullable | |
| og_image | varchar nullable | |
| schema_type | varchar nullable | Service, Article, Event, Person… |
| schema_json | json nullable | |

---

### `settings`
Key-value для глобального контента (хедер, футер, меню, метрика).

| Поле | Тип |
|------|-----|
| key | varchar PK |
| value | text nullable |

Ключи: `site_name`, `logo`, `logo_dark`, `social_vk`, `social_instagram`,
`social_youtube`, `footer_copyright`, `footer_legal`,
`menu_header` (JSON), `menu_footer` (JSON),
`yandex_metrica_id`, `google_analytics_id`,
`cards_page_title`, `cards_page_content`,
`about_title`, `about_content`, `about_img`

---

### Системные (Laravel стандарт)
`users`, `sessions`, `cache`, `job_queue` (переименован), `personal_access_tokens`, `media` (Spatie)

> Формы (callback, contact) — только код: FormRequest → Mail, без сохранения в БД.

---

## Роутинг по субдомену

```
extra.new          → welcome (список клубов)
piter.extra.new    → home клуба piter
matros.extra.new   → home клуба matros
de-vision.new      → home клуба de-vision
```

**Middleware `ResolveClub`:**
```php
$subdomain = explode('.', $request->getHost())[0];
$club = Taxonomy::where('type', 'club')->where('slug', $subdomain)->first();
app()->instance('current_club', $club); // null для extra.new
```

**Scopes на модели Post:**
```php
scopeForClub($club)          // только посты этого клуба
scopeForClubOrGlobal($club)  // посты клуба + посты без клуба
scopeActive()                // status = 10
scopeOrdered()               // by position, затем by date
```

---

## Привязка к шаблонам

| Шаблон | Данные |
|--------|--------|
| [`pages/welcome.blade.php`](../../resources/views/pages/welcome.blade.php) | `Taxonomy::club()->with('settingPost')->get()` |
| [`pages/home.blade.php`](../../resources/views/pages/home.blade.php) | club setting post + banners (shares, is_banner) + shares |
| [`pages/club/index.blade.php`](../../resources/views/pages/club/index.blade.php) | club setting post + banners |
| [`pages/services/index.blade.php`](../../resources/views/pages/services/index.blade.php) | `Post::service()->active()->ordered()` |
| [`pages/services/show.blade.php`](../../resources/views/pages/services/show.blade.php) | Post by slug + seo |
| [`pages/news/index.blade.php`](../../resources/views/pages/news/index.blade.php) | `Post::news()->active()->paginate(10)` |
| [`pages/news/show.blade.php`](../../resources/views/pages/news/show.blade.php) | Post by slug + seo + related |
| [`pages/shares/index.blade.php`](../../resources/views/pages/shares/index.blade.php) | `Post::share()->active()->paginate(12)` |
| [`pages/shares/show.blade.php`](../../resources/views/pages/shares/show.blade.php) | Post by slug + seo + related |
| [`pages/trainers/index.blade.php`](../../resources/views/pages/trainers/index.blade.php) | trainers + specialization taxonomies (фильтр) |
| [`pages/trainers/show.blade.php`](../../resources/views/pages/trainers/show.blade.php) | Post by slug + terms + seo |
| [`pages/events/index.blade.php`](../../resources/views/pages/events/index.blade.php) | upcoming events + past events (paginate) |
| [`pages/jobs/index.blade.php`](../../resources/views/pages/jobs/index.blade.php) | `Post::job()->active()->ordered()` |
| [`pages/card/type.blade.php`](../../resources/views/pages/card/type.blade.php) | `Post::card()->active()->ordered()` + settings |

---

## Порядок миграций

```
Блок 1 — Системные
  → переименовать jobs → job_queue в миграции
  → users, sessions, cache, personal_access_tokens

Блок 2 — Контент
  → settings (key-value)
  → taxonomies
  → posts (+ поля setting: tel, email, address, coordinates, hours)
  → post_term (pivot)
  → seo (полиморфная)

Блок 3 — Медиа
  → media (Spatie Media Library)
```

---

## Статус
**Фаза:** Готово к реализации
**Следующий шаг:** промпт для Cursor — миграции + модели + middleware + scopes
