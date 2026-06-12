<header class="fixed w-full z-[var(--z-header)] bg-black/80 backdrop-blur border-b border-gray-800">
    <div class="max-w-7xl mx-auto px-4 py-3 flex flex-wrap items-center justify-between gap-3">
        <a href="{{ route('home') }}" class="text-2xl font-heading font-bold uppercase text-[var(--color-primary)] tracking-wide">
            EXTRA
        </a>

        <nav class="hidden md:flex flex-wrap items-center gap-1 font-heading text-sm font-semibold uppercase tracking-wide">
            <details class="relative group">
                <summary class="list-none cursor-pointer px-3 py-2 text-white hover:text-[var(--color-primary)] transition-colors [&::-webkit-details-marker]:hidden">
                    О клубе
                </summary>
                <ul class="absolute left-0 top-full mt-1 min-w-[220px] rounded-md border border-gray-800 bg-black/95 py-2 shadow-lg">
                    <li>
                        <a href="{{ url('/es/club/') }}"
                           class="block px-4 py-2 text-white hover:bg-gray-900 hover:text-[var(--color-primary)] {{ request()->is('es/club', 'es/club/*') ? 'text-[var(--color-primary)]' : '' }}">
                            Обзор клуба
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/es/command/') }}"
                           class="block px-4 py-2 text-white hover:bg-gray-900 hover:text-[var(--color-primary)] {{ request()->is('es/command', 'es/command/*') ? 'text-[var(--color-primary)]' : '' }}">
                            Тренеры
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/es/news/') }}"
                           class="block px-4 py-2 text-white hover:bg-gray-900 hover:text-[var(--color-primary)] {{ request()->is('es/news', 'es/news/*') ? 'text-[var(--color-primary)]' : '' }}">
                            Новости
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/es/events/') }}"
                           class="block px-4 py-2 text-white hover:bg-gray-900 hover:text-[var(--color-primary)] {{ request()->is('es/events', 'es/events/*') ? 'text-[var(--color-primary)]' : '' }}">
                            Мероприятия
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/es/job/') }}"
                           class="block px-4 py-2 text-white hover:bg-gray-900 hover:text-[var(--color-primary)] {{ request()->is('es/job', 'es/job/*') ? 'text-[var(--color-primary)]' : '' }}">
                            Вакансии
                        </a>
                    </li>
                    <li class="my-2 border-t border-gray-800"></li>
                    <li>
                        <a href="https://www.youtube.com/channel/UCCUUiy9ZROCNHBmDvPF-dxw/featured"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="block px-4 py-2 text-white hover:bg-gray-900 hover:text-[var(--color-primary)]">
                            Истории успеха
                        </a>
                    </li>
                    <li>
                        <a href="https://www.youtube.com/channel/UCCUUiy9ZROCNHBmDvPF-dxw/featured"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="block px-4 py-2 text-white hover:bg-gray-900 hover:text-[var(--color-primary)]">
                            Советы тренеров
                        </a>
                    </li>
                </ul>
            </details>

            <a href="{{ url('/card/shares/') }}"
               class="px-3 py-2 text-white hover:text-[var(--color-primary)] transition-colors {{ request()->is('card/shares', 'card/shares/*') ? 'text-[var(--color-primary)]' : '' }}">
                Акции
            </a>
            <a href="{{ url('/services/') }}"
               class="px-3 py-2 text-white hover:text-[var(--color-primary)] transition-colors {{ request()->is('services', 'services/*') ? 'text-[var(--color-primary)]' : '' }}">
                Услуги
            </a>
            <a href="{{ url('/card/type/') }}"
               class="px-3 py-2 text-white hover:text-[var(--color-primary)] transition-colors {{ request()->is('card/type', 'card/type/*') ? 'text-[var(--color-primary)]' : '' }}">
                Абонементы и цены
            </a>
            <a href="{{ url('/#contacts') }}"
               class="px-3 py-2 text-white hover:text-[var(--color-primary)] transition-colors">
                Контакты
            </a>
        </nav>

        <div class="flex items-center gap-3">
            <a href="#"
               onclick="window.dispatchEvent(new CustomEvent('callback-open')); return false;"
               class="md:hidden inline-flex items-center gap-2 rounded px-4 py-2 text-sm font-heading font-bold uppercase bg-[var(--color-primary)] text-black hover:bg-[var(--color-primary-hover)] transition-colors">
                <i class="fa-sharp fa-solid fa-phone-arrow-down-left"></i>
                Обратный звонок
            </a>
            <button type="button"
                    onclick="window.dispatchEvent(new CustomEvent('callback-open'))"
                    class="hidden md:inline-flex items-center rounded px-5 py-2 text-sm font-heading font-bold uppercase bg-[var(--color-primary)] text-black hover:bg-[var(--color-primary-hover)] transition-colors">
                Пробное занятие
            </button>
        </div>
    </div>

    <nav class="md:hidden border-t border-gray-800 px-4 py-2 font-heading text-xs font-semibold uppercase tracking-wide">
        <details>
            <summary class="list-none cursor-pointer py-2 text-white [&::-webkit-details-marker]:hidden">О клубе</summary>
            <ul class="pb-2 space-y-1 pl-2">
                <li><a href="{{ url('/es/club/') }}" class="block py-1 text-gray-300 hover:text-[var(--color-primary)]">Обзор клуба</a></li>
                <li><a href="{{ url('/es/command/') }}" class="block py-1 text-gray-300 hover:text-[var(--color-primary)]">Тренеры</a></li>
                <li><a href="{{ url('/es/news/') }}" class="block py-1 text-gray-300 hover:text-[var(--color-primary)]">Новости</a></li>
                <li><a href="{{ url('/es/events/') }}" class="block py-1 text-gray-300 hover:text-[var(--color-primary)]">Мероприятия</a></li>
                <li><a href="{{ url('/es/job/') }}" class="block py-1 text-gray-300 hover:text-[var(--color-primary)]">Вакансии</a></li>
            </ul>
        </details>
        <div class="flex flex-wrap gap-x-4 gap-y-1 py-2">
            <a href="{{ url('/card/shares/') }}" class="text-gray-300 hover:text-[var(--color-primary)]">Акции</a>
            <a href="{{ url('/services/') }}" class="text-gray-300 hover:text-[var(--color-primary)]">Услуги</a>
            <a href="{{ url('/card/type/') }}" class="text-gray-300 hover:text-[var(--color-primary)]">Абонементы</a>
            <a href="{{ url('/#contacts') }}" class="text-gray-300 hover:text-[var(--color-primary)]">Контакты</a>
        </div>
    </nav>
</header>
