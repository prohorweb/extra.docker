<section class="relative flex items-center justify-center min-h-[520px] overflow-hidden" id="contact">

    {{-- Background videos --}}
    <video muted loop autoplay playsinline class="hidden md:block absolute inset-0 w-full h-full object-cover">
        <source src="/video/test-drive.mp4" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
    </video>
    <video muted loop autoplay playsinline class="block md:hidden absolute inset-0 w-full h-full object-cover">
        <source src="/video/test-drive_mobile.mp4" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
    </video>
    <div class="absolute inset-0 bg-black/60"></div>

    <div class="relative z-10 w-full max-w-7xl mx-auto px-4 py-16">
        <h2 class="text-3xl font-heading font-bold uppercase text-center text-white mb-10">Фитнес тест-драйв</h2>

        <div class="flex flex-col lg:flex-row gap-10 justify-center">
            <div class="lg:w-80 text-white/80 text-lg space-y-4">
                <p>Хотите больше узнать о нашем клубе? Оставьте заявку, и наши менеджеры проведут для вас подробную экскурсию.</p>
                <p>Для тех, кому экскурсии мало, мы предлагаем услугу «фитнес тест-драйв» — безлимитную неделю фитнеса!</p>
            </div>

            <form id="subscribe-form" action="{{ url('/club/subscribe/') }}" method="POST"
                  class="flex flex-col gap-4 w-full lg:w-72"
                  onsubmit="ym(21525628,'reachGoal','zayavka'); ym(21525628,'reachGoal','test_drive'); dataLayer && dataLayer.push({'event':'zayavka'});">
                @csrf

                <div class="subscribe-row">
                    <input type="text" name="name" id="subscribe-name"
                           class="w-full bg-black/60 border border-white/30 text-white placeholder-white/50
                                  px-4 py-3 focus:outline-none focus:border-[var(--color-primary)] transition-colors"
                           placeholder="Ваше имя *">
                </div>

                <div class="subscribe-row">
                    <input type="tel" name="tel" id="subscribe-tel"
                           class="w-full bg-black/60 border border-white/30 text-white placeholder-white/50
                                  px-4 py-3 focus:outline-none focus:border-[var(--color-primary)] transition-colors"
                           placeholder="Ваш телефон *">
                </div>

                <label class="flex items-start gap-2 text-sm text-white/70 cursor-pointer">
                    <input type="checkbox" name="accept" id="soglas" class="mt-1 accent-[var(--color-primary)]">
                    <span>Ознакомлен с <a href="{{ url('/privacy/') }}" target="_blank"
                            class="text-[var(--color-primary)] hover:underline">политикой конфиденциальности</a></span>
                </label>

                <div class="text-center pt-2">
                    <button type="submit"
                            class="px-8 py-3 font-heading font-bold uppercase border border-[var(--color-primary)]
                                   text-[var(--color-primary)] hover:bg-[var(--color-primary)] hover:text-black
                                   transition-colors duration-200">
                        Записаться
                    </button>
                </div>

                <input type="hidden" name="url" value="{{ request()->url() }}">
            </form>
        </div>
    </div>

</section>

@push('scripts')
<script>
(function () {
    var form = document.getElementById('subscribe-form');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        form.querySelectorAll('.subscribe-error').forEach(el => el.remove());

        var name   = form.elements['name'].value.trim();
        var tel    = form.elements['tel'].value.trim();
        var accept = form.elements['accept'].checked;
        var ok     = true;

        function err(rowSel, msg) {
            var row = form.querySelector(rowSel);
            if (row) row.insertAdjacentHTML('beforeend',
                '<p class="subscribe-error text-xs text-red-400 mt-1">' + msg + '</p>');
            ok = false;
        }

        if (!name)                        err('.subscribe-row:first-of-type', 'Поле имя не может быть пустым');
        else if (/[^а-яёА-ЯЁ\s]/i.test(name)) err('.subscribe-row:first-of-type', 'Только кириллица');

        if (!tel)                         err('.subscribe-row:last-of-type', 'Поле телефон не может быть пустым');

        if (!accept) {
            var cb = form.querySelector('label');
            if (cb) cb.insertAdjacentHTML('afterend',
                '<p class="subscribe-error text-xs text-red-400 mt-1">Установите флажок</p>');
            ok = false;
        }

        if (!ok) e.preventDefault();
    });
}());
</script>
@endpush
