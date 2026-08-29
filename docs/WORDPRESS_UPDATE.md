# Обновление WordPress (Docker + Multisite)

Инструкция для проекта **extra.docker**: когда и как обновлять WordPress core и Docker-образ без поломки multisite и темы `extrasport`.

---

## Текущее состояние

| Компонент | Версия / значение |
|-----------|-------------------|
| WordPress core (файлы в `./wordpress`) | **6.4.3** — см. `wordpress/wp-includes/version.php` |
| Docker-образ | `wordpress:6.4-php8.2-fpm-alpine` — см. `docker-compose.yml` |
| PHP | 8.2 (FPM в контейнере `extra_wordpress`) |
| Multisite | domain-based: `extrasport.local`, `devision.local` |

---

## Стратегия: не спешить с 7.1.0

WordPress **7.1** («Mary Lou») вышел 19 августа 2026. Админка может предлагать обновление с **6.4.3 → 7.1**.

**Рекомендация для этой ветки (`feature/wordpress`):**

1. **Сейчас не обновлять** — идёт миграция Yii2 → тема `extrasport`.
2. **Дождаться патча** — например **7.1.1** (исправления после первого релиза major-ветки).
3. **Обновиться одним шагом:** `6.4.3 → 7.1.1` (или актуальный стабильный патч на момент обновления).

Уведомление *«WordPress 7.1 is available!»* в админке на локалке **можно игнорировать** — на работу dev-сайта оно не влияет.

> **Production:** не задерживаться на 6.4 слишком долго — старые ветки со временем перестают получать security-патчи. Перед выкладкой на prod запланировать апдейт.

---

## Как устроен Docker в этом репозитории

```yaml
# docker-compose.yml
wordpress:
  image: wordpress:6.4-php8.2-fpm-alpine
  volumes:
    - ./wordpress:/var/www/html
```

- **Ядро WordPress** лежит в папке `./wordpress` на хосте (bind mount).
- **Контейнер** даёт PHP-FPM и окружение; версия core определяется **файлами в volume**, а не только тегом образа.
- Тема, плагины, `wp-config.php`, uploads — всё в `./wordpress`, при смене образа **не удаляется**.

Образ и core **желательно держать согласованными** (после обновления core — обновить и тег в `docker-compose.yml`).

---

## Когда обновлять

Обновление имеет смысл, когда:

- [ ] Миграция темы и multisite стабильны (формы, карусель, карта, админка «Клуб»).
- [ ] Вышел **патч** после 7.1 (например 7.1.1), а не «голый» 7.1.0.
- [ ] Есть **бэкап** БД и файлов.
- [ ] Есть время на **чеклист проверки** (ниже).

---

## Порядок обновления

### 1. Бэкап

```bash
# База WordPress (имя/пароль — из docker-compose.yml)
docker exec extra_mariadb mysqldump -u wordpress -pwordpress123 wordpress > backup-wordpress-$(date +%Y%m%d).sql

# Файлы (из корня репозитория)
tar -czf backup-wordpress-files-$(date +%Y%m%d).tar.gz wordpress/
```

### 2. Обновить core WordPress

**Вариант A — через админку**

1. `https://extrasport.local/wp-admin/` → **Консоль → Обновления**
2. Обновить до целевой версии (например **7.1.1**)
3. При необходимости повторить на `devision.local` (в multisite обычно достаточно обновления с главного сайта сети)

**Вариант B — WP-CLI** (если установлен в контейнере или локально)

```bash
docker exec -it extra_wordpress wp core update --version=7.1.1 --path=/var/www/html
docker exec -it extra_wordpress wp core update-db --path=/var/www/html
```

Проверка версии:

```bash
grep wp_version wordpress/wp-includes/version.php
```

### 3. Обновить Docker-образ

В `docker-compose.yml` заменить тег образа:

```yaml
wordpress:
  image: wordpress:7.1-php8.2-fpm-alpine   # или 7.1.1-php8.2-fpm-alpine, когда тег появится
```

Перезапуск:

```bash
docker compose pull wordpress
docker compose up -d wordpress
```

> Смена образа **не перезаписывает** `./wordpress`. Обновление файлов core — шаг 2; образ — PHP и окружение.

### 4. Проверка после обновления

| Проверка | extrasport.local | devision.local |
|----------|------------------|----------------|
| Главная, карусель, шапка при прокрутке | ☐ | ☐ |
| Формы (callback, test-drive) + письма | ☐ | ☐ |
| Карта, контакты | ☐ | ☐ |
| Админка **Клуб** (настройки, несколько email) | ☐ | ☐ |
| CPT: banner, share, lead | ☐ | ☐ |
| Пересборка темы: `cd wordpress/wp-content/themes/extrasport && npm run build` | ☐ | |

### 5. Откат (если что-то пошло не так)

1. Восстановить БД из дампа.
2. Восстановить папку `wordpress/` из архива (или `git checkout` для отслеживаемых файлов — **не** для uploads и БД).
3. Вернуть в `docker-compose.yml` образ `wordpress:6.4-php8.2-fpm-alpine` и `docker compose up -d wordpress`.

---

## Частые вопросы

### Можно ли только обновить Docker, не трогая файлы?

Нет как единственный шаг. Тег образа меняет PHP/окружение; версия WordPress в админке — из `./wordpress`. Нужны **оба** шага: core + образ.

### Обновлять сейчас через админку на локалке?

**Не рекомендуется** до завершения миграции. Лучше дождаться **7.1.x** и обновиться по этой инструкции.

### Multisite — отдельно обновлять каждый сайт?

Обычно **нет**: core один на всю сеть. Достаточно обновления с network admin / главного сайта + `wp core update-db`.

---

## Связанные файлы

- [WORDPRESS_SETUP.md](../WORDPRESS_SETUP.md) — установка multisite и темы
- [docker-compose.yml](../docker-compose.yml) — сервис `wordpress`
- [wordpress/wp-includes/version.php](../wordpress/wp-includes/version.php) — текущая версия core

---

*Последнее обновление инструкции: август 2026.*
