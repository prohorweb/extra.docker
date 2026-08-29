# WordPress Migration — ExtraSport Theme

Миграция фронтенда Yii2 (`frontend/`) в кастомную тему **`extrasport`** на WordPress Multisite.

**Ветка:** `feature/wordpress`  
**Тема:** `wordpress/wp-content/themes/extrasport/`  
**Стратегия:** Yii2 — только референс структуры и контента. Bootstrap/jQuery **не переносим**. Вёрстка — **Tailwind CSS v4 + Vite + нативный JavaScript**.

---

## Домены (локально)

| Домен | Blog ID | Клуб | Таблицы БД |
|-------|---------|------|------------|
| `https://extrasport.local` | 1 | EXTRASPORT ТК «ПИТЕР» | `wp_*` |
| `https://devision.local` | 2 | De-vision ТРК «РОДЕО ДРАЙВ» | `wp_2_*` |

Оба домена прописаны в `/etc/hosts` и в `nginx.conf`. Каждый клуб — **отдельный сайт** в Multisite-сети (изолированные CPT, опции, контент).

---

## Архитектура

```
┌─────────────────────────────────────────────────────────────┐
│  nginx (extrasport.local / devision.local → wordpress/)     │
└───────────────────────────┬─────────────────────────────────┘
                            ▼
┌─────────────────────────────────────────────────────────────┐
│  WordPress 6.4 Multisite (extra_new, wp_blogs.domain)       │
│  blog 1: extrasport.local  │  blog 2: devision.local         │
└───────────────────────────┬─────────────────────────────────┘
                            ▼
┌─────────────────────────────────────────────────────────────┐
│  Theme: extrasport                                          │
│  header.php / footer.php + template-parts/layout/*          │
│  Vite → assets/dist/output.css + main.js                    │
└─────────────────────────────────────────────────────────────┘
```

### Где что хранится (важно для Multisite)

| Данные | API | Область |
|--------|-----|---------|
| Профиль клуба (телефон, адрес, соцсети, timer, rules) | `get_option( 'extrasport_club' )` | **каждый сайт** |
| Email-адреса форм клуба | `get_option( 'extrasport_site_settings' )` | **каждый сайт** |
| Аналитика, code_head/body, общий email_from | `get_site_option( 'extrasport_network_settings' )` | **вся сеть** |
| CPT (service, share, group_program, banner) | `wp_posts` / `wp_2_posts` | **каждый сайт** |

Хелперы: `extrasport_get_club()`, `extrasport_update_club()`, `extrasport_get_clubs()` — см. `inc/multisite.php`.

**Не используем** `switch_to_blog()` на фронте — WordPress роутит по `wp_blogs.domain`. Switch только в club switcher (список всех клубов).

---

## Статус фаз

| Фаза | Описание | Статус |
|------|----------|--------|
| 2.5 | Vite + Tailwind CSS v4, сборка | ✅ `6374c02` |
| 3 | Layout: header/footer, navbar, modals, chat | ✅ `a4f950a` |
| 4 | Front page: carousel, video, shares, map | ✅ `a4f950a` |
| 5 | JS-модули (nav, modal, cookie, timer, video) + медиа | ✅ `16b774e` |
| 6 | Правила клуба, AJAX-формы, smartbanner, CPT-страницы | ✅ `51bb03d` |
| 6+ | Multisite per-site options, domain mapping | ✅ `7b337a3` |

### Сделано

- Document shell: `header.php`, `footer.php`
- Layout partials: `template-parts/layout/` (navbar, footer, modals, chat, present-video)
- Front page: `front-page.php` (hero, about, shares, subscribe, contacts + Yandex Map)
- JS-модули: `assets/src/modules/` — nav, modal, chat, forms, carousel, map, cookie-consent, present-video, timer, ajax
- CPT: `service`, `group_program`, `share`, `banner`, `event`
- Архивы/синглы CPT на Tailwind: `/services/`, `/programs/`, `/shares/`
- AJAX-обработчики форм: `inc/form-handlers.php` → `wp_mail`
- Полный текст правил: `inc/rules/piter.php`, `inc/rules/matros.php`
- WordPress Multisite активирован, blog #2 = `devision.local`

### Не переносим

- Bootstrap CSS/JS, jQuery
- `site/welcome` (выбор клуба) и поддомены piter/matros
- reCAPTCHA (placeholder)
- Legacy `assets/css/styles.css` enqueue

### В планах

- Админ-UI для `extrasport_club` / network settings (ACF или customizer)
- Импорт контента из Yii2 БД
- Блог, privacy/legal страницы
- `.docx` правил в `assets/docs/`

---

## Структура темы

```
wordpress/wp-content/themes/extrasport/
├── assets/
│   ├── src/
│   │   ├── input.css          # Tailwind + @theme tokens
│   │   ├── main.js            # entry, imports modules
│   │   └── modules/           # nav, modal, forms, carousel, map, …
│   ├── dist/                  # output.css, main.js (git force-add)
│   ├── img/                   # logo, marker, bg_contact, app-icon
│   ├── images/chat/           # wa, vk, tg
│   └── video/                 # hero, about, test-drive
├── inc/
│   ├── multisite.php          # club options per blog
│   ├── theme-settings.php     # network vs site settings
│   ├── form-handlers.php      # AJAX forms
│   ├── rules.php + rules/     # full club rules text
│   ├── post-types.php
│   └── taxonomies.php
├── template-parts/
│   ├── layout/                # header-nav, footer, modals, widgets
│   └── content-*.php          # CPT cards
├── header.php, footer.php, front-page.php
├── archive-*.php, single-*.php
├── package.json, vite.config.js, tailwind.config.js
└── functions.php
```

---

## Быстрый старт

### 1. Docker

```bash
docker compose up -d
```

Сервисы: `extra_wordpress`, `extra_nginx`, `extra_mariadb`.

### 2. `/etc/hosts`

```
127.0.0.1 extrasport.local
127.0.0.1 devision.local
```

### 3. `wp-config.php`

```bash
cp wordpress/wp-config-sample.php wordpress/wp-config.php
```

БД (актуально для локального окружения):

```php
define( 'DB_NAME', 'extra_new' );
define( 'DB_USER', 'extra' );
define( 'DB_PASSWORD', 'extra123' );
define( 'DB_HOST', 'db' );
```

Multisite-константы — см. блок в `wp-config-sample.php` или `inc/wp-config-multisite.php`.  
**Важно:** `MULTISITE = true` только после `wp core multisite-convert` (таблица `wp_blogs` должна существовать).

### 4. Установка сети (если с нуля)

```bash
docker run --rm --network extradocker_default \
  -v "$(pwd)/wordpress:/var/www/html" \
  wordpress:cli wp core install \
  --url=https://extrasport.local \
  --title='ExtraSport' \
  --admin_user=admin \
  --admin_password=admin123 \
  --admin_email=admin@extrasport.local \
  --allow-root

docker run --rm --network extradocker_default \
  -v "$(pwd)/wordpress:/var/www/html" \
  wordpress:cli wp core multisite-convert \
  --title='ExtraSport Network' \
  --allow-root
```

### 5. Второй клуб (devision)

```bash
docker run --rm --network extradocker_default \
  -v "$(pwd)/wordpress:/var/www/html" \
  wordpress:cli wp site create \
  --slug=devision --title='De-Vision' \
  --email=admin@extrasport.local --allow-root

docker run --rm --network extradocker_default \
  -v "$(pwd)/wordpress:/var/www/html" \
  wordpress:cli db query \
  "UPDATE wp_blogs SET domain='devision.local', path='/' WHERE blog_id=2" \
  --allow-root

docker run --rm --network extradocker_default \
  -v "$(pwd)/wordpress:/var/www/html" \
  wordpress:cli option update home 'https://devision.local' \
  --url=https://devision.local --allow-root

docker run --rm --network extradocker_default \
  -v "$(pwd)/wordpress:/var/www/html" \
  wordpress:cli option update siteurl 'https://devision.local' \
  --url=https://devision.local --allow-root
```

### 6. Тема + сборка фронта

```bash
docker run --rm --network extradocker_default \
  -v "$(pwd)/wordpress:/var/www/html" \
  wordpress:cli theme activate extrasport \
  --url=https://extrasport.local --allow-root

docker run --rm --network extradocker_default \
  -v "$(pwd)/wordpress:/var/www/html" \
  wordpress:cli theme activate extrasport \
  --url=https://devision.local --allow-root

cd wordpress/wp-content/themes/extrasport
npm install
npm run dev    # watch
npm run build  # production
```

### 7. Проверка

- https://extrasport.local/ — главная ExtraSport
- https://devision.local/ — главная De-Vision
- https://extrasport.local/wp-admin — админка сети

---

## Разработка фронта

```bash
cd wordpress/wp-content/themes/extrasport
npm run dev     # vite build --watch
npm run build   # production → assets/dist/
```

Брендовые токены в `assets/src/input.css`:

```css
--color-brand-primary: #ff6600;
--color-brand-dark: #141416;
--color-brand-accent: #dc5800;
```

Font Awesome — CDNJS (free). Иконки `fa-sharp` из Yii2 заменены на `fa-solid`.

---

## Полезные WP-CLI команды

```bash
# Список сайтов сети
docker run --rm --network extradocker_default \
  -v "$(pwd)/wordpress:/var/www/html" \
  wordpress:cli site list --allow-root

# Опции клуба на сайте
docker run --rm --network extradocker_default \
  -v "$(pwd)/wordpress:/var/www/html" \
  wordpress:cli option get extrasport_club --format=json \
  --url=https://extrasport.local --allow-root

# Засеять дефолты клуба
docker run --rm --network extradocker_default \
  -v "$(pwd)/wordpress:/var/www/html" \
  wordpress:cli eval 'extrasport_seed_club_option(1); extrasport_seed_club_option(2);' \
  --url=https://extrasport.local --allow-root

# Создать CPT-запись
docker run --rm --network extradocker_default \
  -v "$(pwd)/wordpress:/var/www/html" \
  wordpress:cli post create --post_type=share \
  --post_title='Акция' --post_status=publish \
  --url=https://extrasport.local --allow-root
```

---

## Troubleshooting

### Error establishing a database connection

**Причина:** `MULTISITE = true` в `wp-config.php`, но сеть не установлена (нет `wp_blogs`).

**Решение:** выполнить `wp core multisite-convert` или временно закомментировать `MULTISITE` до установки сети.

### Сайт devision.local показывает extrasport

**Причина:** в `wp_blogs` для blog #2 указан неверный `domain`/`path`.

**Решение:**

```sql
UPDATE wp_blogs SET domain='devision.local', path='/' WHERE blog_id=2;
```

### Cookie banner / формы не работают

Пересобрать assets: `npm run build`. Проверить в Network, что `main.js` и `output.css` отдают 200.

### Видео / фон contacts 404

Убедиться, что файлы есть в `assets/video/` и `assets/img/bg_contact.jpeg` (коммитятся в git).

---

## Ссылки

- Yii2 референс: `frontend/views/layouts/`, `frontend/views/site/index.php`
- Docker: `docker-compose.yml`, `nginx.conf`
- Общий README репозитория: [README.md](README.md)
