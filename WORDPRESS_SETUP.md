# WordPress Multisite Setup Guide

## ⚙️ Инициализация WordPress

### 1. Создать wp-config.php

```bash
cd /Users/prohor/Projects/extra.docker
cp wordpress/wp-config-sample.php wordpress/wp-config.php
```

### 2. Поднять Docker (если ещё не запущено)

```bash
docker-compose up -d
```

Проверить логи:
```bash
docker-compose logs -f wordpress
```

### 3. Инициализировать WordPress Core

```bash
docker-compose exec wordpress wp core install \
  --url=http://extrasport.local \
  --title='ExtraSport' \
  --admin_user=admin \
  --admin_password=admin123 \
  --admin_email=admin@extrasport.local \
  --allow-root
```

### 4. Активировать Multisite

**Важно:** После предыдущего шага WordPress скажет вам что нужно добавить в wp-config.php. Скопируйте эти строки и добавьте в `wordpress/wp-config.php` перед строкой `/* That's all, stop editing! */`.

Или автоматически:

```bash
docker-compose exec wordpress wp multisite-convert \
  --title='ExtraSport Network' \
  --allow-root
```

### 5. Добавить домены в `/etc/hosts`

На вашей машине (macOS):

```bash
sudo nano /etc/hosts
```

Добавить:
```
127.0.0.1 extrasport.local
127.0.0.1 devision.local
127.0.0.1 wp.local
```

Сохранить (Ctrl+X, Y, Enter)

### 6. Активировать тему на основном сайте

```bash
docker-compose exec wordpress wp theme activate extrasport \
  --url=http://extrasport.local \
  --allow-root
```

### 7. Создать второй сайт (De-Vision)

```bash
docker-compose exec wordpress wp site create \
  --url=http://devision.local \
  --title='De-Vision' \
  --email=admin@devision.local \
  --allow-root
```

Активировать тему на втором сайте:

```bash
docker-compose exec wordpress wp theme activate extrasport \
  --url=http://devision.local \
  --allow-root
```

### 8. Создать примеры контента

#### Создать баннер

```bash
docker-compose exec wordpress wp post create \
  --post_type=banner \
  --post_title='First Banner' \
  --post_status=publish \
  --url=http://extrasport.local \
  --allow-root
```

#### Создать акцию (Share)

```bash
docker-compose exec wordpress wp post create \
  --post_type=share \
  --post_title='Summer Promo' \
  --post_content='Special offer for summer' \
  --post_status=publish \
  --url=http://extrasport.local \
  --allow-root
```

### 9. Открыть в браузере

- **Главная ExtraSport:** http://extrasport.local/
- **Админка ExtraSport:** http://extrasport.local/wp-admin
- **Главная De-Vision:** http://devision.local/
- **Админка De-Vision:** http://devision.local/wp-admin

Логин: `admin`  
Пароль: `admin123`

---

## 🔧 Полезные команды

### Проверить статус WordPress

```bash
docker-compose exec wordpress wp core is-installed --allow-root && echo "✅ WordPress installed"
```

### Список сайтов Multisite

```bash
docker-compose exec wordpress wp site list --allow-root
```

### Очистить кеш объектов

```bash
docker-compose exec wordpress wp cache flush --allow-root
```

### Очистить все данные и начать заново

```bash
docker-compose down -v
docker-compose up -d
# Затем повторить шаги 3-8
```

### Посмотреть логи WordPress

```bash
docker-compose logs wordpress -f
```

### Подключиться к контейнеру WordPress

```bash
docker-compose exec wordpress sh
```

---

## 📁 Структура файлов

```
wordpress/
├── wp-config.php ← ваша конфигурация (создаётся из wp-config-sample.php)
├── wp-config-sample.php ← шаблон
├── wp-content/
│   └── themes/
│       └── extrasport/ ← ваша тема
├── wp-admin/ ← стандартные файлы WordPress
├── wp-includes/ ← стандартные файлы WordPress
└── .htaccess ← правила переписи URL для Multisite
```

---

## 🚀 Дальнейшие шаги

После инициализации WordPress:

1. **ФАЗА 3** — Перенос CSS и JavaScript из Yii2
2. **ФАЗА 4** — Миграция контента из Yii2 БД
3. **ФАЗА 5** — Адаптация шаблонов под вёрстку Yii2
4. **ФАЗА 6** — Интеграция Forms и функциональности
5. **ФАЗА 7** — Тестирование и полировка

---

## ❓ Проблемы и решения

### Проблема: WordPress показывает 404 на главной

**Решение:** Перегенерировать permalink структуру:
```bash
docker-compose exec wordpress wp rewrite flush --allow-root
```

### Проблема: Мультисайт не работает

**Решение:** Убедиться что:
1. wp-config.php содержит MULTISITE = true
2. .htaccess правильно настроен
3. Домены добавлены в /etc/hosts

### Проблема: Ошибка "Cookies blocked"

**Решение:** Убедиться что COOKIEDOMAIN установлен на `.local`

### Проблема: Админ-панель недоступна

**Решение:** Проверить права доступа:
```bash
docker-compose exec wordpress chmod -R 755 wp-content/
```

---

**Автор:** GitHub Copilot  
**Дата:** 2026-08-29  
**Статус:** ✅ Готово к использованию
