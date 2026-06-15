{{-- Top info bar --}}
<section class="w-full border-b border-gray-900">
    <div class="max-w-7xl mx-auto px-4 md:flex items-center justify-between py-2 text-sm">
        <div class="flex items-center gap-4 text-white/90">
            <span class="text-[var(--color-primary)] font-medium">Ваш клуб:</span>
            <a href="#" onclick="window.dispatchEvent(new CustomEvent('club-modal-open')); return false;"
               class="flex items-center gap-1 hover:text-white transition-colors">
                {{ $club->address ?? '- - -' }}
                <i class="fa-solid fa-chevron-down text-xs"></i>
            </a>
        </div>
        <a href="tel:{{ $club->tel ?? '' }}"
           class="text-[var(--color-primary)] hover:text-[var(--color-primary-hover)] transition-colors">
            <i class="fa-solid fa-phone text-xs"></i> {{ $club->tel ?? '' }}
        </a>
    </div>
</section>

{{-- Main header --}}
<header id="mainNav"
    class="sticky top-0 w-full z-[var(--z-header)] bg-black/80 backdrop-blur-md border-b border-gray-900 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between py-4">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center">
                <img src="/img/logo.svg" alt="EXTRA SPORT" class="h-14 md:h-16 transition-all duration-300">
            </a>

            {{-- Desktop nav --}}
            <nav class="hidden lg:flex items-center gap-8 font-heading text-md font-semibold uppercase tracking-widest">
                <div class="relative group">
                    <button onclick="this.nextElementSibling.classList.toggle('hidden')"
                        class="flex items-center gap-1 text-white hover:text-[var(--color-primary)] transition-colors">
                        О КЛУБЕ
                        <i class="fa-solid fa-chevron-down text-xs transition-transform group-hover:rotate-180"></i>
                    </button>
                    <ul class="absolute text-sm left-0 top-full mt-2 hidden w-56 bg-black/95 border border-gray-700 rounded py-2 shadow-2xl z-[var(--z-header)]">
                        <li><a href="{{ url('/es/club/') }}" class="block px-5 py-2 hover:bg-gray-900 hover:text-[var(--color-primary)]">Обзор клуба</a></li>
                        <li><a href="{{ url('/es/command/') }}" class="block px-5 py-2 hover:bg-gray-900 hover:text-[var(--color-primary)]">Тренеры</a></li>
                        <li><a href="{{ url('/es/news/') }}" class="block px-5 py-2 hover:bg-gray-900 hover:text-[var(--color-primary)]">Новости</a></li>
                        <li><a href="{{ url('/es/events/') }}" class="block px-5 py-2 hover:bg-gray-900 hover:text-[var(--color-primary)]">Мероприятия</a></li>
                        <li><a href="{{ url('/es/job/') }}" class="block px-5 py-2 hover:bg-gray-900 hover:text-[var(--color-primary)]">Вакансии</a></li>
                        <li class="border-t border-gray-700 my-1"></li>
                        <li><a href="https://www.youtube.com/channel/UCCUUiy9ZROCNHBmDvPF-dxw/featured" target="_blank" rel="noopener noreferrer" class="block px-5 py-2 hover:bg-gray-900 hover:text-[var(--color-primary)]">Истории успеха</a></li>
                        <li><a href="https://www.youtube.com/channel/UCCUUiy9ZROCNHBmDvPF-dxw/featured" target="_blank" rel="noopener noreferrer" class="block px-5 py-2 hover:bg-gray-900 hover:text-[var(--color-primary)]">Советы тренеров</a></li>
                    </ul>
                </div>

                <a href="{{ url('/card/shares/') }}"
                   class="text-white hover:text-[var(--color-primary)] transition-colors {{ request()->is('card/shares*') ? 'text-[var(--color-primary)]' : '' }}">АКЦИИ</a>
                <a href="{{ url('/services/') }}"
                   class="text-white hover:text-[var(--color-primary)] transition-colors {{ request()->is('services*') ? 'text-[var(--color-primary)]' : '' }}">УСЛУГИ</a>
                <a href="{{ url('/card/type/') }}"
                   class="text-white hover:text-[var(--color-primary)] transition-colors {{ request()->is('card/type*') ? 'text-[var(--color-primary)]' : '' }}">АБОНЕМЕНТЫ И ЦЕНЫ</a>
                <a href="{{ url('/#contacts') }}"
                   class="text-white hover:text-[var(--color-primary)] transition-colors">КОНТАКТЫ</a>
            </nav>

            {{-- Right side --}}
            <div class="flex items-center gap-4">
                <button onclick="window.dispatchEvent(new CustomEvent('callback-open'))"
                    class="hidden lg:flex items-center gap-2 border border-[var(--color-primary)] text-[var(--color-primary)] hover:bg-[var(--color-primary)] hover:text-black font-bold uppercase px-6 py-2.5 rounded transition-all text-sm tracking-wider">
                    <i class="fa-solid fa-mobile-vibrate"></i>
                    ОБРАТНЫЙ ЗВОНОК
                </button>

                <a href="#" onclick="window.dispatchEvent(new CustomEvent('callback-open')); return false;"
                   class="lg:hidden flex items-center justify-center w-10 h-10 text-[var(--color-primary)] border border-[var(--color-primary)] rounded hover:bg-[var(--color-primary)] hover:text-black transition-all">
                    <i class="fa-solid fa-mobile-vibrate"></i>
                </a>

                <button id="mobile-menu-btn" onclick="openOffcanvas()" class="lg:hidden text-white text-2xl">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile offcanvas --}}
    <div id="mobileOffcanvas" class="hidden fixed inset-0 bg-black/95 z-[9999] lg:hidden overflow-y-auto">
        <div class="p-5">
            <div class="flex justify-between items-center mb-8">
                <a href="{{ route('home') }}">
                    <img src="/img/logo.svg" alt="EXTRA SPORT" class="h-14">
                </a>
                <button onclick="closeOffcanvas()" class="text-4xl text-white leading-none">&times;</button>
            </div>

            {{-- Club info --}}
            <div class="mb-8 p-4 bg-gray-900/50 rounded">
                <div class="flex items-center gap-2 text-[var(--color-primary)] mb-2">
                    <i class="fa-solid fa-location-dot"></i>
                    <span class="font-medium">Ваш клуб</span>
                </div>
                <a href="#" onclick="window.dispatchEvent(new CustomEvent('club-modal-open')); closeOffcanvas(); return false;"
                   class="text-white block hover:text-[var(--color-primary)] transition-colors">
                    {{ $club->address ?? '- - -' }}
                    <i class="fa-solid fa-chevron-down text-xs ml-1"></i>
                </a>
                <a href="tel:{{ $club->tel ?? '' }}"
                   class="block mt-3 text-[var(--color-primary)] hover:text-[var(--color-primary-hover)] transition-colors">
                    {{ $club->tel ?? '' }}
                </a>
            </div>

            <nav class="space-y-6 text-white font-heading uppercase text-base">
                <details class="group">
                    <summary class="flex justify-between py-3 border-b border-white/10 cursor-pointer list-none">
                        О КЛУБЕ
                        <span class="transition-transform group-open:rotate-90">›</span>
                    </summary>
                    <ul class="pl-4 pt-4 space-y-4 text-white/80 text-sm">
                        <li><a href="{{ url('/es/club/') }}" class="hover:text-[var(--color-primary)]">Обзор клуба</a></li>
                        <li><a href="{{ url('/es/command/') }}" class="hover:text-[var(--color-primary)]">Тренеры</a></li>
                        <li><a href="{{ url('/es/news/') }}" class="hover:text-[var(--color-primary)]">Новости</a></li>
                        <li><a href="{{ url('/es/events/') }}" class="hover:text-[var(--color-primary)]">Мероприятия</a></li>
                        <li><a href="{{ url('/es/job/') }}" class="hover:text-[var(--color-primary)]">Вакансии</a></li>
                    </ul>
                </details>

                <a href="{{ url('/card/shares/') }}" class="block py-3 border-b border-white/10 hover:text-[var(--color-primary)]">АКЦИИ</a>
                <a href="{{ url('/services/') }}" class="block py-3 border-b border-white/10 hover:text-[var(--color-primary)]">УСЛУГИ</a>
                <a href="{{ url('/card/type/') }}" class="block py-3 border-b border-white/10 hover:text-[var(--color-primary)]">АБОНЕМЕНТЫ И ЦЕНЫ</a>
                <a href="{{ url('/#contacts') }}" class="block py-3 border-b border-white/10 hover:text-[var(--color-primary)]">КОНТАКТЫ</a>

                <button onclick="window.dispatchEvent(new CustomEvent('callback-open')); closeOffcanvas()"
                    class="w-full font-heading mt-8 bg-[var(--color-primary)] text-black font-bold py-4 rounded tracking-wider hover:bg-[var(--color-primary-hover)] transition-colors">
                    ОБРАТНЫЙ ЗВОНОК
                </button>
            </nav>
        </div>
    </div>
</header>

<script>
    function openOffcanvas() {
        document.getElementById('mobileOffcanvas').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeOffcanvas() {
        document.getElementById('mobileOffcanvas').classList.add('hidden');
        document.body.style.overflow = '';
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeOffcanvas();
    });
</script>
