{{--
@component x-ui.card
@prop bool $interactive (default: true)
@slot image — Изображение (optional)
@slot content — Основной контент (required)
@slot footer — Нижняя часть (optional)
@slot badge — Бейдж в углу (optional)
--}}

<article
    class="rounded-2xl bg-gray-900 border border-gray-800 shadow-lg transition-shadow duration-300 {{ ($interactive ?? true) ? 'hover:shadow-xl cursor-pointer' : '' }}"
>
    @if(isset($image))
        <div class="relative overflow-hidden rounded-t-2xl">
            {{ $image }}
            @if(isset($badge))
                <div class="absolute top-3 left-3">{{ $badge }}</div>
            @endif
        </div>
    @endif
    <div class="p-6">
        {{ $content }}
    </div>
    @if(isset($footer))
        <div class="px-6 pb-6 pt-0">{{ $footer }}</div>
    @endif
</article>