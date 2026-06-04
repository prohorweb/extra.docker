# extra.docker

Docker-окружение для миграции фитнес-клуба **ExtraSport** с Yii2 Advanced на Laravel 11 по паттерну **Strangler Fig**.

Проект позволяет постепенно заменять страницы Yii2 на Laravel без простоя: nginx маршрутизирует уже готовые маршруты на Laravel, а всё остальное — на legacy-приложение.

## Архитектура

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
| `extra_phpmyadmin` | phpmyadmin/phpmyadmin | 8081 |

## Базы данных

- **`extra`** — legacy-база Yii2 (не трогаем до финального импорта)
- **`extra_new`** — новая база Laravel (разрабатываем здесь)

Перед финальным переключением на Laravel данные из `extra` будут импортированы в `extra_new` через artisan-команду.

## Установка

См. [docs/installation.md](docs/installation.md) — настройка доменов, HTTPS, запуск контейнеров, инициализация Laravel и Yii2, полезные команды и доступы.

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