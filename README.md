# extra.docker

Полноценное Docker-окружение для мультидоменного проекта на Yii2 Advanced. 
Проект настроен для локальной работы по адресам:
* Основной сайт: **http://extra.local/**
* Региональный поддомен: **http://piter.extra.local/**

Окружение полностью совместимо с Windows (включая WSL2) и macOS (Intel и Apple Silicon M1/M2/M3).

## Архитектура окружения
* **Nginx** (контейнер `extra_nginx`) — веб-сервер, порт `80` (с поддержкой поддоменов)
* **PHP-FPM 8.2** (контейнер `extra_php`) — с расширениями `gd`, `pdo_mysql`, `zip` и встроенным **Composer 2**
* **MariaDB 11** (контейнер `extra_mariadb`) — база данных, порт `3306`
* **phpMyAdmin** (контейнер `extra_phpmyadmin`) — веб-интерфейс БД, порт `8081`

---

## Быстрый запуск (Развертывание на новом устройстве / MacBook)

### Шаг 1. Настройка локальных доменов на хосте (Обязательно)
Чтобы ваш браузер знал, куда перенаправлять запросы `extra.local`, добавьте домены в системный файл `hosts`.

* **На macOS (в терминале Mac):**
  ```bash
  sudo nano /etc/hosts
  ```
* **На Windows (запустить Блокнот от Администратора):**
  Открыть файл: `C:\Windows\System32\drivers\etc\hosts`

Добавьте в самый конец файла следующие строки и сохраните:
```text
127.0.0.1 extra.local
127.0.0.1 piter.extra.local
```

### Шаг 2. Клонирование и сборка контейнеров
```bash
git clone https://github.com
cd extra.docker

# Запуск сборки под архитектуру вашего процессора (Intel или Apple Silicon)
docker compose up -d --build
```

### Шаг 3. Инициализация Yii2 окружения
Запустите скрипт инициализации внутри PHP-контейнера. Выберите вариант `0` (Development) и подтвердите действие (`yes`):
```bash
docker compose exec php php init
```

### Шаг 4. Установка PHP-пакетов
Установка всех зависимостей проекта выполняется напрямую внутри PHP-контейнера:
```bash
docker compose exec php composer update
```

### Шаг 5. Применение миграций базы данных
```bash
docker compose exec php php yii migrate
```

---

## Доступ к сервисам проекта

* **Основной сайт:** [http://extra.local/](http://extra.local/)
* **Региональный сайт (Питер):** [http://piter.extra.local/](http://piter.extra.local/)
* **phpMyAdmin:** [http://localhost:8081](http://localhost:8081)
  * **Логин:** `root`
  * **Пароль:** `root123`

---

## Полезные команды

* **Остановка проекта:** `docker compose down`
* **Остановка с полной очисткой БД (удаление volume):** `docker compose down -v`
* **Установка пакета через Composer:** 
  ```bash
  docker compose exec php composer require vendor/package-name
  ```

