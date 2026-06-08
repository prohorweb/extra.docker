{{--
@component x-modals.club-select
Модальное окно выбора клуба.
--}}

{{-- Заглушка — будет реализовано позже --}}
<div x-data="{ open: false }" @club-select-open.window="open = true" @keydown.escape="open = false">
    <template x-teleport="body">
        <div x-show="open" class="fixed inset-0 z-[var(--z-modal-backdrop)] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" @click.self="open = false" x-trap.noscroll="open">
            <div class="bg-[var(--color-surface)] rounded-2xl p-6 max-w-2xl w-full shadow-[var(--shadow-elevated)]">
                <h2 class="text-xl font-bold mb-4">Выберите клуб</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="http://piter.extra.new" class="block p-4 border border-[var(--color-surface-border)] rounded-xl hover:border-[var(--color-brand)] transition">ТРЦ «Питер»</a>
                    <a href="http://matros.extra.new" class="block p-4 border border-[var(--color-surface-border)] rounded-xl hover:border-[var(--color-brand)] transition">«Матроса Железняка»</a>
                    <a href="http://de-vision.new" class="block p-4 border border-[var(--color-surface-border)] rounded-xl hover:border-[var(--color-brand)] transition">De-Vision</a>
                </div>
                <div class="mt-4 flex justify-end">
                    <x-ui.button variant="ghost" @click="open = false">Закрыть</x-ui.button>
                </div>
            </div>
        </div>
    </template>
</div>