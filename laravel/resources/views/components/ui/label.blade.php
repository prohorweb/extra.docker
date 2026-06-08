{{--
@component x-ui.label
@prop string $for (optional)
@prop bool $required (default: false)
--}}

<label
    for="{{ $for ?? '' }}"
    {{ $attributes->merge(['class' => 'block text-sm font-medium text-gray-300 mb-1']) }}
>
    {{ $slot }}
    @if($required ?? false)
        <span class="text-red-500 ml-1">*</span>
    @endif
</label>