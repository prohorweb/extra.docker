<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <x-seo.meta :data="$seo ?? null" />
    @if(isset($theme) && is_array($theme))
        <style>
            :root {
                @foreach($theme as $key => $value)
                    {{ $key }}: {{ $value }};
                @endforeach
            }
        </style>
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="bg-black text-white antialiased font-body">
    @if($showHeader ?? true)
        @include('layouts.parts.header')
    @endif

    <main>
        @yield('content')
    </main>

    @include('layouts.parts.footer')

    <x-modals.callback />
    <x-modals.club-select />

    @stack('scripts')
</body>
</html>
