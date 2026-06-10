"# Domain: Home (Главная страница)

## Description
Главная страница сайта фитнес-клуба EXTRASPORT. Самый сложный и посещаемый шаблон системы. Содержит большое количество секций, медиа-контента, динамических данных и JavaScript-логики.

## Current State (Yii2)
Файл: `frontend/views/site/index.php`

### Используемые переменные:
- `$club` — информация о текущем клубе (subdomain)
- `$shares` — акции клуба
- `$banners_club` — баннеры/слайды главной страницы
- `$settings` — мета-теги, настройки
- `$metros` — ближайшие станции метро
- `$this->params['club']` — используется в шаблоне

### Секции на странице:
1. **Header + Mega Slider** (карусель с видео + баннерами)
2. **About / Service Video** (видео-секция с текстом)
3. **Actions (Акции)** — карточки акций с ссылками
4. **Subscribe Block** (`/club/_subscribe`)
5. **Contacts + Map** (Яндекс.Карта + контакты клуба)

### Технологии в Yii2:
- Bootstrap 5 Carousel
- Yandex Maps API 2.1
- DeviceDetect (мобильная/десктопная версия)
- Отдельные JS-файлы: `nav.js`, `parallax.js`, `carousel-wheel.js`
- Прямые SQL-запросы и ActiveRecord модели

## Migration Goals (Laravel 12)

### Цели:
- Перевести на современный Blade Components + Tailwind CSS
- Убрать дублирование кода (отдельные мобильные/десктопные карусели)
- Сделать код типизированным и поддерживаемым
- Вынести бизнес-логику в Service/DTO/Repository
- Сохранить весь визуальный функционал и производительность

### Components to create:
- `x-layout.navigation`
- `x-sections.hero` (или `x-sections.main-slider`)
- `x-sections.services-video`
- `x-sections.actions` (акции клуба)
- `x-sections.subscribe`
- `x-sections.contacts` (с картой)
- `x-ui.card`

### Data Sources (Laravel models):
- `Club`
- `Share` (или `Promotion`)
- `ClubBanner`
- `Setting`
- `Metro`

## Migration Strategy
- Будем переносить **секция за секцией**
- Сначала — Hero/Slider (самая сложная часть)
- Затем — Actions, Subscribe, Map
- В конце — объединение в `home.blade.php` и настройка роутинга

## Current Task
**In Progress**: Анализ и планирование переноса главной страницы

## Next Steps
1. Полный разбор секции Hero + Slider
2. Создание `HomeController` и передача данных
3. Создание первого компонента `x-sections.hero`

**Status**: Analysis Phase
**Priority**: High
**Complexity**: High"