# ФАЗА 1 & 2: WordPress Setup & Шлюз для Multisite

## Статус: ✅ ЗАВЕРШЕНА

### Корректировка логики сайта (по запросу):

#### ✅ Исключено:
- Страница `site/welcome` (выбор клуба) 
- Поддомены (piter.extra.new, matros.extra.new и т.д.)

#### ✅ Реализовано:
- **Каждый домен открывает свою главную:**
  - `http://extrasport.local/` → главная ExtraSport
  - `http://devision.local/` → главная De-Vision
- **Структура главной страницы (из Yii2 site/index.php):**
  1. Carousel (баннеры)
  2. About (видео)
  3. Actions/Shares (акции)
  4. Subscribe форма
  5. Map + Контакты

### Выполненные задачи:

#### **1. Адаптация front-page.php** ✅
- Перенесена полная разметка из `frontend/views/site/index.php`
- Bootstrap carousel для баннеров (desktop + mobile версии)
- Секция акций (Shares)
- Форма подписки
- Контакты с Яндекс.Картой (подготовлено)
- Вся разметка использует `get_posts()` для Multisite совместимости

#### **2. Custom Post Types расширены** ✅
- `banner` — баннеры на главной (не публичные)
- `share` — акции и предложения
- `service` — услуги
- `group_program` — программы/абонементы
- `event` — события

#### **3. WordPress Multisite конфигурация** ✅
- `wp-config-sample.php` обновлена с Multisite настройками:
  - `MULTISITE = true`
  - `SUBDOMAIN_INSTALL = false` (domain-based, не subdomain-based)
  - `DOMAIN_CURRENT_SITE = 'extrasport.local'`
  - `COOKIEDOMAIN = '.local'` (поддержка всех .local доменов)
- `.htaccess` создан для WordPress Multisite

#### **4. Структура готова для миграции** ✅
- `front-page.php` готов для наполнения контентом из Yii2
- Шаблоны Services и Programs остаются отдельными страницами (не на главной)
- Главная страница сосредоточена на информации о клубе и акциях

### Следующие шаги:

1. **Создать wp-config.php** из wp-config-sample.php
2. **Инициализировать WordPress:**
   ```bash
   docker-compose exec wordpress wp core install \
     --url=http://extrasport.local \
     --title='ExtraSport' \
     --admin_user=admin \
     --admin_password=admin123 \
     --admin_email=admin@extrasport.local \
     --allow-root
   ```
3. **Создать второй сайт Multisite (Devision):**
   ```bash
   docker-compose exec wordpress wp site create \
     --title='De-Vision' \
     --slug=devision.local \
     --allow-root
   ```
4. **Активировать тему на обоих сайтах:**
   ```bash
   docker-compose exec wordpress wp theme activate extrasport \
     --network \
     --allow-root
   ```
5. **Создать примеры контента** (баннеры, акции)
6. **Добавить домены в `/etc/hosts`:**
   ```
   127.0.0.1 extrasport.local
   127.0.0.1 devision.local
   ```

### Файловая структура темы:

```
extrasport/
├── front-page.php ✅ (адаптирована под Yii2 структуру)
├── functions.php ✅ (расширена)
├── inc/post-types.php ✅ (5 CPT)
├── inc/taxonomies.php ✅ (2 taxonomies)
├── template-parts/ ✅ (4 файла)
├── assets/
│   ├── css/style.css ✅
│   └── js/main.js ✅
└── ... другие файлы
```

### Важные замечания:

1. **Multisite конфигурация:**
   - Domain-based (не subdomain) для простоты
   - Каждый клуб на своём домене (extrasport.local, devision.local)
   - Общая тема (extrasport), разные контенты

2. **Главная страница:**
   - Показывает только информацию клуба, баннеры, акции
   - Services и Programs находятся на отдельных страницах
   - Контакты и карта в подвале

3. **Навигация:**
   - Каждый сайт имеет свою админку
   - Контент полностью изолирован между клубами
   - Тема одна, может быть обновлена для всех сайтов сразу

### Как запустить:

```bash
# 1. Скопировать конфиг
cp wordpress/wp-config-sample.php wordpress/wp-config.php

# 2. Поднять Docker (если ещё не запущено)
docker-compose up -d

# 3. Инициализировать WordPress
docker-compose exec wordpress wp core install \
  --url=http://extrasport.local \
  --title='ExtraSport' \
  --admin_user=admin \
  --admin_password=admin123 \
  --admin_email=admin@extrasport.local \
  --allow-root

# 4. Добавить домены в /etc/hosts
sudo nano /etc/hosts
# 127.0.0.1 extrasport.local
# 127.0.0.1 devision.local

# 5. Открыть в браузере
# http://extrasport.local/ (главная)
# http://extrasport.local/wp-admin (админ)
```

---

**Дата завершения:** 2026-08-29  
**Статус:** ✅ ГОТОВО К ФАЗЕ 3 (Вёрстка & Assets)
