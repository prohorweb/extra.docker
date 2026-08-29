# ФАЗА 1: Инициализация WordPress Multisite в Docker

## Статус: ✅ ЗАВЕРШЕНА

### Выполненные задачи:

#### 1. Git & Ветка ✅
- [x] Создана ветка `feature/wordpress`
- [x] Ветка готова к коммитам

#### 2. Docker Compose ✅
- [x] Добавлен сервис WordPress (образ `wordpress:6.4-php8.2-fpm-alpine`)
- [x] Примонтирована директория `./wordpress:/var/www/html`
- [x] Подключена база данных MariaDB
- [x] Обновлены зависимости Nginx
- [x] Конфигурация окружения:
  - DB_HOST: `db`
  - DB_NAME: `wordpress`
  - DB_USER: `wordpress`
  - DB_PASSWORD: `wordpress123`
  - PHP_MEMORY_LIMIT: `256M`

#### 3. Nginx Configuration ✅
- [x] Добавлен блок сервера для WordPress
- [x] Поддержка multisite доменов (*.local)
- [x] Upstream для PHP-FPM (`extra_wordpress:9000`)
- [x] Маршруты для `/wp-*`, `/wp-json` маршруток
- [x] Статические файлы с кешированием
- [x] Поддержка WordPress REST API

#### 4. Структура темы WordPress ✅

**Основные файлы:**
- [x] `style.css` — заголовок и мета-информация темы
- [x] `functions.php` — регистрация функций, подключение assets, CPT, taxonomies
- [x] `index.php` — fallback шаблон
- [x] `header.php` — заголовок с логотипом и навигацией
- [x] `footer.php` — подвал с виджетами и меню
- [x] `front-page.php` — главная страница (с услугами и программами)
- [x] `page.php` — шаблон обычной страницы
- [x] `single.php` — шаблон одного поста
- [x] `archive.php` — шаблон архива

**Специфичные шаблоны (CPT):**
- [x] `single-service.php` — вид услуги
- [x] `archive-service.php` — архив услуг с фильтрацией
- [x] `single-group-program.php` — вид программы/абонемента
- [x] `archive-group-program.php` — архив программ с фильтрацией

**Template Parts:**
- [x] `template-parts/content.php` — карточка поста
- [x] `template-parts/content-service.php` — карточка услуги
- [x] `template-parts/content-group_program.php` — карточка программы
- [x] `template-parts/content-none.php` — сообщение "ничего не найдено"

**Includes (функции):**
- [x] `inc/post-types.php` — регистрация Custom Post Types:
  - `service` — Услуги
  - `group_program` — Программы/абонементы
  - `event` — События (опционально)
- [x] `inc/taxonomies.php` — регистрация Taxonomies:
  - `service_category` — Категории услуг
  - `program_type` — Типы программ

**Assets:**
- [x] `assets/css/style.css` — основные стили темы
- [x] `assets/js/main.js` — основной JavaScript (инициализация, фильтры, навигация)
- [x] `assets/images/` — директория для изображений (пустая)
- [x] `assets/fonts/` — директория для шрифтов (пустая)

**Конфигурация:**
- [x] `.gitignore` — исключение файлов WordPress
- [x] `wp-config-sample.php` — шаблон конфигурации

### Структура файловой системы:

```
wordpress/
├── wp-content/
│   └── themes/
│       └── extrasport/
│           ├── assets/
│           │   ├── css/
│           │   │   └── style.css (✅ создан)
│           │   ├── js/
│           │   │   └── main.js (✅ создан)
│           │   ├── images/ (📁 пустая)
│           │   └── fonts/ (📁 пустая)
│           ├── template-parts/
│           │   ├── content.php (✅ создан)
│           │   ├── content-service.php (✅ создан)
│           │   ├── content-group_program.php (✅ создан)
│           │   └── content-none.php (✅ создан)
│           ├── inc/
│           │   ├── post-types.php (✅ создан)
│           │   └── taxonomies.php (✅ создан)
│           ├── style.css (✅ создан)
│           ├── functions.php (✅ создан)
│           ├── index.php (✅ создан)
│           ├── header.php (✅ создан)
│           ├── footer.php (✅ создан)
│           ├── front-page.php (✅ создан)
│           ├── page.php (✅ создан)
│           ├── single.php (✅ создан)
│           ├── archive.php (✅ создан)
│           ├── single-service.php (✅ создан)
│           ├── archive-service.php (✅ создан)
│           ├── single-group-program.php (✅ создан)
│           └── archive-group-program.php (✅ создан)
├── .gitignore (✅ создан)
└── wp-config-sample.php (✅ создан)
```

### Следующие шаги (ФАЗА 2+):

1. **ФАЗА 2** — Регистрация Custom Post Types и Taxonomies ✅ (встроено в functions.php)
2. **ФАЗА 3** — Доработка вёрстки и assets (копирование стилей из Yii2)
3. **ФАЗА 4** — Адаптация шаблонов под реальную вёрстку Yii2
4. **ФАЗА 5** — Миграция данных из Yii2 БД в WordPress
5. **ФАЗА 6** — Настройка WordPress Multisite и доменов
6. **ФАЗА 7** — Финальное тестирование

### Как запустить Docker:

```bash
# Перейти в директорию проекта
cd /Users/prohor/Projects/extra.docker

# Поднять контейнеры
docker-compose up -d

# Проверить логи WordPress
docker-compose logs -f wordpress

# Доступ к WordPress
http://extrasport.local/wp-admin
http://devision.local/wp-admin
```

### Основные домены (для локальной разработки):

- `extrasport.local` — Основной сайт Extrasport
- `devision.local` — Сайт Devision
- `wp.local` — Админ-панель WordPress

**Примечание:** На локальной машине нужно добавить эти домены в `/etc/hosts`:
```
127.0.0.1 extrasport.local
127.0.0.1 devision.local
127.0.0.1 wp.local
```

---

**Дата завершения:** 2026-08-29  
**Автор:** GitHub Copilot  
**Статус:** ✅ ГОТОВО К ФАЗЕ 2
