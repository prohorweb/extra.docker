# extra.docker

Yii2 проект в Docker: `nginx` + `php-fpm` + `mariadb` + `phpmyadmin`.

## Текущая схема

- `Dockerfile` не используется.
- PHP работает из локального образа `extra-php:8.2-gd`.
- В образ уже включены нужные расширения (`gd`, `pdo_mysql`, `mbstring`, `zip` и т.д.).

## Сервисы

- `php` -> `extra-php:8.2-gd`
- `nginx` -> `nginx:latest` (порт `8080`)
- `db` -> `mariadb:11`
- `phpmyadmin` -> `phpmyadmin/phpmyadmin` (порт `8081`)
- `composer` -> `composer:2` (одноразовый сервис)

## Быстрый запуск

```bash
docker compose up -d
```

Проверка:

```bash
docker compose ps
docker compose exec php php -m
curl -I http://127.0.0.1:8080/
```

## Доступ

- Сайт: `http://<server-ip>:8080`
- phpMyAdmin: `http://<server-ip>:8081`

## Composer

```bash
docker compose run --rm composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
```

## Пересоздать только web-часть

```bash
docker compose up -d --force-recreate php nginx
```

## Если снова нужно пересобрать PHP-образ

1. Временно запустить `php:8.2-fpm` с установкой расширений.
2. Зафиксировать контейнер в образ:

```bash
docker commit extra_php extra-php:8.2-gd
```

3. Убедиться, что в `docker-compose.yml` у `php`:

```yaml
image: extra-php:8.2-gd
command: ["php-fpm"]
```

4. Перезапустить сервис:

```bash
docker compose up -d --force-recreate php nginx
```

## Очистка старых образов

Удалить все неиспользуемые образы:

```bash
docker image prune -a -f
```

Показать оставшиеся:

```bash
docker images
```

## База данных

Полный сброс БД и повторная инициализация из `db/`:

```bash
docker compose down -v
docker compose up -d
```
