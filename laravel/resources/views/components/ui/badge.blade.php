{{--
@component x-ui.badge
@prop string $variant (default: brand) — brand, success, warning, danger
--}}

@php
$variantClasses = [
    'brand' => 'bg-yellow-500 text-black',
    'success' => 'bg-green-500 text-white',
    'warning' => 'bg-orange-500 text-white',
    'danger' => 'bg-red-500 text-white',
];
@endphp

<span
    {{ $attributes->merge(['class' => 'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold ' . ($variantClasses[$variant ?? 'brand'] ?? $variantClasses['brand'])]) }}
>
    {{ $slot }}
</span>