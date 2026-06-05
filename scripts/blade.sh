cat > setup-blade.sh << 'SCRIPT_EOF'
#!/bin/bash

echo "🚀 Создание структуры Blade..."

# Создаем директории
mkdir -p laravel/resources/views/{layouts,pages,sections,components,partials}
mkdir -p laravel/resources/css
mkdir -p laravel/resources/js

# 1. Главный Layout
cat > laravel/resources/views/layouts/app.blade.php << 'EOF'
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extra Fitness</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-black text-white antialiased">
    
    @include('components.header')
    
    <main>
        @yield('content')
    </main>

    @include('components.footer')

</body>
</html>
EOF

# 2. Страница выбора клуба (Welcome)
cat > laravel/resources/views/pages/welcome.blade.php << 'EOF'
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-900 pt-20">
    <div class="text-center px-4">
        <h1 class="text-4xl md:text-5xl font-bold mb-8 text-yellow-500">Выберите свой клуб</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
            <!-- Заглушка для карточек клубов -->
            <a href="/club/piter" class="block p-8 border border-gray-700 rounded hover:border-yellow-500 transition cursor-pointer group">
                <h2 class="text-2xl font-bold group-hover:text-yellow-500">Piter</h2>
                <p class="text-gray-400 mt-2">Санкт-Петербург</p>
            </a>
            <a href="/club/de-vision" class="block p-8 border border-gray-700 rounded hover:border-yellow-500 transition cursor-pointer group">
                <h2 class="text-2xl font-bold group-hover:text-yellow-500">De-Vision</h2>
                <p class="text-gray-400 mt-2">Москва</p>
            </a>
        </div>
    </div>
</div>
@endsection
EOF

# 3. Страница клуба (Index) - заглушка
cat > laravel/resources/views/pages/home.blade.php << 'EOF'
@extends('layouts.app')

@section('content')
<!-- Hero Section Placeholder -->
<section class="h-screen bg-gray-800 flex items-center justify-center">
    <h1 class="text-4xl md:text-6xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-red-600">
        HERO SECTION
    </h1>
</section>

<!-- Services Placeholder -->
<section class="py-20 bg-black">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-bold mb-10 text-center">Наши услуги</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="h-64 bg-gray-900 rounded"></div>
            <div class="h-64 bg-gray-900 rounded"></div>
            <div class="h-64 bg-gray-900 rounded"></div>
        </div>
    </div>
</section>
@endsection
EOF

# 4. Компоненты (Header/Footer - минимальные)
cat > laravel/resources/views/components/header.blade.php << 'EOF'
<header class="fixed w-full z-50 bg-black/80 backdrop-blur border-b border-gray-800">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <a href="/" class="text-2xl font-bold text-yellow-500 hover:opacity-80 transition">EXTRA</a>
        <nav class="hidden md:flex space-x-6">
            <a href="#" class="hover:text-yellow-500 transition">О клубе</a>
            <a href="#" class="hover:text-yellow-500 transition">Услуги</a>
            <a href="#" class="hover:text-yellow-500 transition">Контакты</a>
        </nav>
        <button class="bg-yellow-500 text-black px-6 py-2 rounded font-bold hover:bg-yellow-400 transition">
            Пробное занятие
        </button>
    </div>
</header>
EOF

cat > laravel/resources/views/components/footer.blade.php << 'EOF'
<footer class="bg-gray-900 py-10 border-t border-gray-800">
    <div class="container mx-auto px-4 text-center text-gray-500">
        &copy; {{ date('Y') }} Extra Fitness. All rights reserved.
    </div>
</footer>
EOF

# 5. Базовый CSS (Tailwind директивы)
cat > laravel/resources/css/app.css << 'EOF'
@tailwind base;
@tailwind components;
@tailwind utilities;

/* Глобальные стили */
body {
    font-family: 'Inter', sans-serif;
}
EOF

# 6. Базовый JS (Vite entry)
cat > laravel/resources/js/app.js << 'EOF'
import './bootstrap';
// Здесь позже подключим Alpine, GSAP, Lenis
console.log('Laravel Frontend Loaded');
EOF

# 7. Базовый bootstrap.js (нужен для Vite)
cat > laravel/resources/js/bootstrap.js << 'EOF'
import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
EOF

echo "✅ Структура создана!"
SCRIPT_EOF

chmod +x setup-blade.sh && ./blade.sh