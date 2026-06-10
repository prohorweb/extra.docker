# Domain: Map

## Description
Компонент карты с Яндекс.Картами, метками и фильтром инверсии.

## Stack
- Tailwind v4
- Yandex Maps JS API 2.1
- Laravel Blade
- MutationObserver (фильтр)

## Dependencies
- `resources/views/components/sections/map.blade.php`
- `public/uploads/layout/icones/marker.png`
- `config/services.yandex_maps.api_key` (опционально)

## Migration status
✅ Laravel component ready
❌ Yii2 integration not started (new feature — migration not needed)

## Notes
- API-ключ не настроен — работает без ключа
- Все метки используют `marker.png`
- Фильтр применяется через MutationObserver, не через CSS
- Метки: EXTRASPORT Питер, Июнь, De‑vision

## Known issues
- `marker2.png` отсутствует (нужен, если для De‑vision нужна другая иконка)
- `$model` и `$model2` должны передаваться в компонент явно