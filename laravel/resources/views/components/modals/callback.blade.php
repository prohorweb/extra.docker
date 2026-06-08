{{--
@component x-modals.callback
Модальное окно обратного звонка.
Вызывается из layout глобально.
--}}

<div x-data="{ open: false }" @callback-open.window="open = true" @keydown.escape="open = false">
    <template x-teleport="body">
        <div x-show="open" class="fixed inset-0 z-[var(--z-modal-backdrop)] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" @click.self="open = false" x-trap.noscroll="open">
            <div class="bg-[var(--color-surface)] rounded-2xl p-6 max-w-md w-full shadow-[var(--shadow-elevated)]">
                <h2 class="text-xl font-bold mb-4">Обратный звонок</h2>
                <p class="text-[var(--color-text-secondary)] mb-4">Оставьте номер, мы перезвоним в ближайшее время.</p>
                <form>
                    <x-ui.input name="phone" placeholder="+7 (XXX) XXX-XX-XX" required />
                    <div class="mt-4 flex justify-end gap-3">
                        <x-ui.button variant="ghost" @click="open = false">Отмена</x-ui.button>
                        <x-ui.button type="submit">Отправить</x-ui.button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>