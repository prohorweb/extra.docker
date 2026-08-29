# extra.docker

Docker-окружение для миграции фитнес-клуба **ExtraSport** с Yii2 Advanced.

В репозитории параллельно ведутся два направления:

1. **WordPress Multisite** (ветка `feature/wordpress`) — основной фронтенд на теме `extrasport` (Tailwind + Vite)
2. **Laravel 11 Strangler Fig** — постепенная замена страниц Yii2 на Laravel

---

## WordPress Migration (актуально)

**Подробная документация:** [WORDPRESS_SETUP.md](WORDPRESS_SETUP.md)

| Домен | Клуб | Blog ID |
|-------|------|---------|
| `https://extrasport.local` | EXTRASPORT ТК «ПИТЕР» | 1 |
| `https://devision.local` | De-vision ТРК «РОДЕО ДРАЙВ» | 2 |

**Стек темы:** Tailwind CSS v4, Vite, нативный JS (без Bootstrap/jQuery).  
**Тема:** `wordpress/wp-content/themes/extrasport/`  
**БД:** `extra_new` (Multisite: `wp_*` + `wp_2_*`)

### Статус фаз WordPress

| Фаза | Описание | Статус |
|------|----------|--------|
| 2.5 | Vite + Tailwind | ✅ |
| 3 | Layout (header, footer, modals, chat) | ✅ |
| 4 | Front page (carousel, map, forms) | ✅ |
| 5 | JS-модули + медиа | ✅ |
| 6 | Правила, AJAX-формы, CPT-страницы, Multisite options | ✅ |

### Быстрый старт WordPress

```bash
docker compose up -d

# /etc/hosts: 127.0.0.1 extrasport.local devision.local

cd wordpress/wp-content/themes/extrasport
npm install && npm run build
```

Открыть: https://extrasport.local/ , https://devision.local/

---

## Laravel Strangler Fig (legacy track)

Проект позволяет постепенно заменять страницы Yii2 на Laravel без простоя: nginx маршрутизирует уже готовые маршруты на Laravel, а всё остальное — на legacy-приложение.

## Архитектура (Laravel)

```
┌─────────────────────────────────────────────────────────┐
│                    Nginx (порт 80/443)                  │
│         HTTPS через mkcert (локально доверенный)        │
├─────────────────────────────────────────────────────────┤
│                                                         │
│   / (главная)  ──────►  Laravel 11 (extra_laravel)     │
│   /services    ──────►  Laravel 11 (по готовности)     │
│   /shares      ──────►  Laravel 11 (по готовности)     │
│                                                         │
│   /admin/*     ──────►  Yii2 Backend (extra_php)       │
│   всё остальное ────►  Yii2 Frontend (extra_php)       │
│                                                         │
└─────────────────────────────────────────────────────────┘
              │                        │
              ▼                        ▼
         MariaDB 11               MariaDB 11
        (extra_new)               (extra)
```

## Домены (локальная разработка)

| Домен | Назначение | Обработчик |
|-------|-----------|------------|
| `https://extra.new` | Главная — выбор клуба | **Laravel** |
| `https://piter.extra.new` | Клуб Piter | **Laravel** |
| `https://matros.extra.new` | Клуб Matros | **Laravel** |
| `https://de-vision.new` | Клуб De-Vision | **Laravel** |
| `https://extra.new/admin` | Админка Yii2 | Yii2 Backend |
| `http://localhost:8090` | Прямой доступ к Laravel (dev) | Laravel |
| `http://localhost:8081` | phpMyAdmin | phpMyAdmin |

## Сервисы

| Контейнер | Образ | Порт |
|-----------|-------|------|
| `extra_nginx` | nginx:latest | 80, 443, 8090 |
| `extra_php` | extra-php:8.2-gd | 9000 (Yii2) |
| `extra_laravel` | extra-php:8.2-gd | 9000 (Laravel) |
| `extra_mariadb` | mariadb:11 | 3306 |
| `extra_wordpress` | wordpress:6.4-php8.2-fpm-alpine | 9000 (WordPress) |
| `extra_phpmyadmin` | phpmyadmin/phpmyadmin | 8081 |

## Базы данных

- **`extra`** — legacy-база Yii2 (не трогаем до финального импорта)
- **`extra_new`** — Laravel + **WordPress Multisite** (разрабатываем здесь)

Перед финальным переключением на Laravel данные из `extra` будут импортированы в `extra_new` через artisan-команду.

## Установка

- WordPress + Multisite: [WORDPRESS_SETUP.md](WORDPRESS_SETUP.md)
- Laravel + Yii2 + HTTPS: [docs/installation.md](docs/installation.md)

## Статус миграции (Strangler Fig)

- [x] **Фаза 0:** Параллельный запуск Yii2 + Laravel в одном Docker
- [x] **Фаза 1.1:** Перехват главной `/` на Laravel
- [x] **Фаза 1.2:** HTTPS через mkcert
- [x] **Фаза 1.3:** Мультидоменность (extra.new + субдомены)
- [ ] **Фаза 2:** Модели, миграции, сиды для клубов/услуг/акций
- [ ] **Фаза 3:** Filament-админка
- [ ] **Фаза 4:** Перехват остальных маршрутов (/services, /shares, ...)
- [ ] **Фаза 5:** Финальный импорт данных из `extra` → `extra_new` + переключение

## Стек

- **Laravel 11** + Filament 3 + Blade + Alpine.js + GSAP + Lenis + Vite + Tailwind
- **Yii2 Advanced** (legacy, постепенно заменяется)
- **MariaDB 11** + **Redis**
- **Docker** + **nginx** (Strangler Fig routing)
- **HTTPS** через mkcert (локально доверенные сертификаты)