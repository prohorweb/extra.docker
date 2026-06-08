{{--
@component x-ui.input
@prop string $name (required)
@prop string $type (default: text)
@prop string $placeholder
@prop bool $required (default: false)
--}}

<input
    name="{{ $name }}"
    type="{{ $type ?? 'text' }}"
    placeholder="{{ $placeholder ?? '' }}"
    {{ $required ?? false ? 'required' : '' }}
    {{ $attributes->merge(['class' => 'w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition']) }}
/>