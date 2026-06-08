{{--
@component x-ui.modal
@prop bool $open (default: false)
@prop string $title (required)
@prop string $size (default: md) — sm, md, lg, xl, full
@event close
--}}

@php
$maxWidth = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    'full' => 'max-w-full mx-4',
];
@endphp

<div x-data="{ open: {{ ($open ?? false) ? 'true' : 'false' }} }" @open.window="open = true" @close.window="open = false" @keydown.escape="open = false">
    <template x-teleport="body">
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-[var(--z-modal-backdrop)] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" @click.self="open = false" x-trap.noscroll="open" role="dialog" aria-modal="true" aria-labelledby="modal-title">
            <div class="w-full {{ $maxWidth[$size ?? 'md'] }} bg-gray-900 rounded-2xl shadow-xl overflow-hidden">
                <div class="flex items-center justify-between p-4 border-b border-gray-800">
                    <h2 id="modal-title" class="text-xl font-bold">{{ $title }}</h2>
                    <button @click="open = false" class="text-gray-400 hover:text-white text-2xl leading-none p-1" aria-label="Закрыть">&times;</button>
                </div>
                <div class="p-6">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </template>
</div>