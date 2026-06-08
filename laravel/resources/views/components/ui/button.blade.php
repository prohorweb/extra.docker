@props([
    'variant' => 'brand',
    'size' => 'md',
    'disabled' => false,
    'href' => null,
    'type' => 'button',
])

@php
$classes = [
    'base' => 'inline-flex items-center justify-center gap-2 rounded-xl font-semibold transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none',
    'variant' => [
        'brand' => 'bg-yellow-500 text-black hover:bg-yellow-400 focus-visible:ring-yellow-500',
        'outline' => 'border-2 border-gray-700 text-white hover:bg-gray-800 hover:border-yellow-500 focus-visible:ring-yellow-500',
        'ghost' => 'text-gray-400 hover:text-white hover:bg-gray-800 focus-visible:ring-gray-600',
    ],
    'size' => [
        'sm' => 'px-4 py-2 text-sm rounded-lg',
        'md' => 'px-5 py-3 text-base rounded-xl',
        'lg' => 'px-8 py-4 text-lg rounded-2xl',
    ],
];
$tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }}
    {{ $attributes->merge(['class' => implode(' ', [
        $classes['base'],
        $classes['variant'][$variant],
        $classes['size'][$size],
    ])]) }}
    @if($tag === 'button') type="{{ $type }}" @endif
    @if($disabled) disabled @endif
    @if($href) href="{{ $href }}" @endif
>
    {{ $slot }}
</{{ $tag }}>