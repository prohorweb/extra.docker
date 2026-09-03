# WordPress Migration — ExtraSport Theme

Миграция фронтенда Yii2 (`frontend/`) в кастомную тему **`extrasport`** на WordPress Multisite.

**Ветка:** `feature/wordpress`  
**Тема:** `deploy/wp-content/themes/extrasport/`  
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
│  nginx → /var/www/wordpress (wp_core + theme mount)         │
└───────────────────────────┬─────────────────────────────────┘
                            ▼
┌─────────────────────────────────────────────────────────────┐
│  WordPress 7.1 Multisite (образ + volume wp_core)           │
│  blog 1: extrasport.local  │  blog 2: devision.local         │
└───────────────────────────┬─────────────────────────────────┘
                            ▼
┌─────────────────────────────────────────────────────────────┐
│  Theme: extrasport (bind-mount)                             │
│  Vite → assets/dist/output.css + main.js                    │
└─────────────────────────────────────────────────────────────┘
```

**Deploy:** core из Docker-образа, тема из git, БД — SQL-импорт. См. [deploy/README.md](deploy/README.md).

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
- Полный текст правил: `inc/rules/extrasport.php`, `inc/rules/devision.php`
- WordPress Multisite активирован, blog #2 = `devision.local`

### Не переносим

- Bootstrap CSS/JS, jQuery
- Yii2 поддомены (`piter`, `matros`, `june`, `polus`) — в WP только **extrasport** и **devision**
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
deploy/wp-content/themes/extrasport/
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
docker compose up -d db wordpress nginx
```

Сервисы: `extra_wordpress`, `extra_nginx`, `extra_mariadb`.

WordPress core — из образа `wordpress:7.1-php8.2-fpm-alpine` (volume `wp_core`).  
Конфиг: `deploy/wp-config.php`.

### 2. `/etc/hosts`

```
127.0.0.1 extrasport.local
127.0.0.1 devision.local
```

### 3. База данных

Импорт при деплое (не из `./db/`):

```bash
chmod +x deploy/scripts/wordpress-import-db.sh
./deploy/scripts/wordpress-import-db.sh path/to/wordpress-dump.sql
```

Переменные БД — в `docker-compose.yml` или `.env.wordpress.example`.

### 4. Установка сети (если с нуля)

Используйте wp-cli с volume `wp_core` и темой:

```bash
docker run --rm --network extra-docker_default \
  -v extra-docker_wp_core:/var/www/html \
  -v "$(pwd)/deploy/wp-content/themes/extrasport:/var/www/html/wp-content/themes/extrasport" \
  -v "$(pwd)/deploy/wp-config.php:/var/www/html/wp-config.php:ro" \
  wordpress:cli wp core install \
  --url=https://extrasport.local \
  --title='ExtraSport' \
  --admin_user=admin \
  --admin_password=admin123 \
  --admin_email=admin@extrasport.local \
  --allow-root

docker run --rm --network extra-docker_default \
  -v extra-docker_wp_core:/var/www/html \
  -v "$(pwd)/deploy/wp-config.php:/var/www/html/wp-config.php:ro" \
  wordpress:cli wp core multisite-convert \
  --title='ExtraSport Network' \
  --allow-root
```

> Имя сети и volume (`extra-docker_*`) может отличаться — проверьте `docker network ls` и `docker volume ls`.

### 5. Второй клуб (devision)

```bash
docker run --rm --network extra-docker_default \
  -v extra-docker_wp_core:/var/www/html \
  -v "$(pwd)/deploy/wp-config.php:/var/www/html/wp-config.php:ro" \
  wordpress:cli wp site create \
  --slug=devision --title='De-Vision' \
  --email=admin@extrasport.local --allow-root

docker run --rm --network extra-docker_default \
  -v extra-docker_wp_core:/var/www/html \
  -v "$(pwd)/deploy/wp-config.php:/var/www/html/wp-config.php:ro" \
  wordpress:cli db query \
  "UPDATE wp_blogs SET domain='devision.local', path='/' WHERE blog_id=2" \
  --allow-root
```

### 6. Тема + сборка фронта

```bash
cd deploy/wp-content/themes/extrasport
npm install
npm run dev    # watch
npm run build  # production
```

Активация темы (если нужно):

```bash
docker run --rm --network extra-docker_default \
  -v extra-docker_wp_core:/var/www/html \
  -v "$(pwd)/deploy/wp-content/themes/extrasport:/var/www/html/wp-content/themes/extrasport" \
  -v "$(pwd)/deploy/wp-config.php:/var/www/html/wp-config.php:ro" \
  wordpress:cli theme activate extrasport \
  --url=https://extrasport.local --allow-root
```

### 7. Проверка

- https://extrasport.local/ — главная ExtraSport
- https://devision.local/ — главная De-Vision
- https://extrasport.local/wp-admin — админка сети

---

## Разработка фронта

```bash
cd deploy/wp-content/themes/extrasport
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
- **Обновление WordPress (7.1.x, Docker):** [docs/WORDPRESS_UPDATE.md](docs/WORDPRESS_UPDATE.md)
- Общий README репозитория: [README.md](README.md)
