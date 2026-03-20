# extra.docker

Описание проекта и запуск в Docker + nginx + MariaDB для `extra.local` + субдоменов.

## 1. Цель

- Перенести существующий Yii2-проект из Apache на nginx в Docker.
- Использовать базу данных из папки `db`.
- Работать на хостах:
  - `extra.local`
  - `piter.extra.local`
  - `matros.extra.local`

## 2. Файлы  концовка

- `docker-compose.yml` (стандартные образы)
- `nginx.conf`
- `frontend/config/main.php`
- `frontend/config/main-local.php`

---

## 3. /etc/hosts (Windows)

```text
127.0.0.1 extra.local
127.0.0.1 piter.extra.local
127.0.0.1 matros.extra.local
```

---

## 4. Dockerfile (не обязательно)

Используется стандартный образ `php:8.2-fpm`, поэтому Dockerfile можно не использовать — сборка не нужна. В рамках ветки оставлен для справки, но не обязателен.

---

## 5. docker-compose.yml

```yaml
version: "3.8"
services:
  php:
    image: php:8.2-fpm
    container_name: extra_php
    volumes:
      - ./:/var/www/html
    depends_on:
      - db
    environment:
      DB_HOST: db
      DB_PORT: 3306
      DB_NAME: extra
      DB_USER: extra
      DB_PASS: extra123

  composer:
    image: composer:2
    container_name: extra_composer
    working_dir: /app
    volumes:
      - ./:/app
    depends_on:
      - php
    restart: "no"
    command: ['install', '--no-dev', '--prefer-dist', '--optimize-autoloader', '--no-interaction']

  nginx:
    image: nginx:latest
    container_name: extra_nginx
    ports:
      - "8080:80"
    depends_on:
      - php
    volumes:
      - ./:/var/www/html:ro
      - ./nginx.conf:/etc/nginx/conf.d/default.conf:ro

  db:
    image: mariadb:11
    container_name: extra_mariadb
    restart: always
    environment:
      MARIADB_ROOT_PASSWORD: root123
      MARIADB_DATABASE: extra
      MARIADB_USER: extra
      MARIADB_PASSWORD: extra123
    volumes:
      - db_data:/var/lib/mysql
      - ./db:/docker-entrypoint-initdb.d

volumes:
  db_data:
```

---

## 6. nginx.conf

```nginx
server {
    listen 80;
    server_name extra.local piter.extra.local matros.extra.local;

    root /var/www/html;
    index index.php index.html index.htm;

    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    location ~ \.php$ {
        fastcgi_pass php:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param SCRIPT_NAME $fastcgi_script_name;
    }

    location ~ /\.(ht|git) {
        deny all;
    }

    access_log /var/log/nginx/access.log;
    error_log /var/log/nginx/error.log;
}
```

---

## 7. config/main-local.php (DB-компоненты)

```php
return [
    'components' => [
        'request' => [
            'cookieValidationKey' => 'zrQb9sAZgjb2Zu3k5iTCtSCkdcx-qHrV',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_NAME'),
            'username' => getenv('DB_USER'),
            'password' => getenv('DB_PASS'),
            'charset' => 'utf8',
        ],
        'db4' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_NAME'),
            'username' => getenv('DB_USER'),
            'password' => getenv('DB_PASS'),
            'charset' => 'utf8',
        ],
    ],
];
```

---

## 8. config/main.php (поддомены)

- `piter` → `db`
- `matros` → `db4`
- `iyun` и `polyus` удалены

Код внутри `on beforeRequest`:

```php
$sub = explode('.', $_SERVER['HTTP_HOST'])[0];
if ($sub == 'piter') {
    Yii::$app->set('db', Yii::$app->get('db'));
} elseif ($sub == 'matros') {
    Yii::$app->set('db', Yii::$app->get('db4'));
}
```

---

## 9. Запуск

```bash
docker compose down -v
# установить зависимости (composer один раз)
docker compose run --rm composer
# поднять сервисы
docker compose up -d
```

Открыть:
- http://extra.local:8080
- http://piter.extra.local:8080
- http://matros.extra.local:8080

При обновлении зависимостей:

```bash
docker compose run --rm composer
```
---

## 10. Перезапуск БД

```bash
docker compose down
docker volume rm extra_docker_db_data
docker compose up -d --build
```

---

## 11. Composer + чистый PHP8 (стабильная схема)

1. Убедиться, что в `composer.json`:
   - `"php": "^8.0"`
   - `symfony/mailer` + `symfony/http-client` вместо `swiftmailer`
   - нет старых abandoned пакетов (`yii2-recaptcha`, `yii2-sitemap`, `yii2-jquery-loading`, `yii2-ckeditor5` и т.п.)

2. Запустить:

```bash
composer update -W --prefer-dist --optimize-autoloader --no-interaction
```

3. Проверка локально:

```bash
composer validate
composer audit
```

4. Пересборка контейнера:

```bash
docker compose down -v
docker compose build --no-cache
docker compose up -d
```

5. Проверка внутри контейнера:

```bash
docker compose exec php php -v
docker compose exec php ls -la /var/www/html/vendor | head
```

6. Если нужно восстановить базу и права:

```bash
docker compose exec php sh -c "chown -R www-data:www-data /var/www/html && chmod -R 775 /var/www/html/runtime /var/www/html/web/assets"
```
