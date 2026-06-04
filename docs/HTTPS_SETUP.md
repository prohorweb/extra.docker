# Настройка HTTPS через mkcert

Домены `.new` требуют обязательного HTTPS — это политика Chrome/HSTS для TLD, принадлежащего Google. Без валидного сертификата браузер просто откажется открывать страницу.

**Решение:** `mkcert` создаёт локально доверенные сертификаты, которые браузеры принимают без предупреждений (зелёный замочек 🔒).

## Установка (один раз на машину)

### macOS
```bash
brew install mkcert nss
mkcert -install   # устанавливает локальный CA в систему
```

### Windows (через Chocolatey)
```bash
choco install mkcert
mkcert -install
```

### Linux
```bash
# Debian/Ubuntu
sudo apt install libnss3-tools
curl -JLO "https://dl.filippo.io/mkcert/latest?for=linux/amd64"
chmod +x mkcert-v*-linux-amd64
sudo cp mkcert-v*-linux-amd64 /usr/local/bin/mkcert

mkcert -install
```

После `mkcert -install` локальный корневой сертификат добавляется в системное хранилище доверенных — браузеры будут принимать все сертификаты, подписанные им.

## Генерация сертификатов для проекта

```bash
cd extra.docker
mkdir -p docker/nginx/ssl
cd docker/nginx/ssl

# Создаём сертификат для всех доменов проекта
mkcert extra.new "*.extra.new" de-vision.new "*.de-vision.new" localhost 127.0.0.1 ::1

# Переименовываем для удобства
mv extra.new+5.pem cert.pem
mv extra.new+5-key.pem key.pem
cd ../../..
```

⚠️ Папка `docker/nginx/ssl/` добавлена в `.gitignore` — сертификаты не коммитятся.

## Перезпуск контейнеров

```bash
docker compose down
docker compose up -d
```

## Проверка

```bash
# Должен вернуть 301 → HTTPS
curl -I http://extra.new

# Должен вернуть 200 OK с зелёным замком
curl -I https://extra.new
```

## Если сертификат не доверяется

1. Убедись, что `mkcert -install` выполнен
2. Полностью перезапусти браузер:
   - Chrome: `chrome://restart`
   - Или закрой все окна и открой заново
3. Проверь корневой сертификат:
   ```bash
   mkcert -CAROOT
   ```
   Открой файл `rootCA.pem` из этой папки — в macOS должен быть статус **"Always Trust"**.

## Добавление нового домена

Если позже понадобится добавить домен (например, `newclub.extra.new`), перегенерируй сертификат:

```bash
cd docker/nginx/ssl
rm cert.pem key.pem

mkcert extra.new "*.extra.new" de-vision.new "*.de-vision.new" newclub.extra.new localhost 127.0.0.1 ::1

mv extra.new+6.pem cert.pem
mv extra.new+6-key.pem key.pem

docker compose restart nginx
```

## Альтернатива: использовать `.test` вместо `.new`

Если не хочется возиться с HTTPS, можно заменить все `.new` на `.test` — этот TLD зарезервирован специально для локальной разработки и не требует HTTPS.

Но мы выбрали `.new`, чтобы локальные домены совпадали с реальными — это упрощает перенос конфигов на продакшн.