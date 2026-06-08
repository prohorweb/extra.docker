@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-900">
    <div class="text-center">
        <h1 class="text-5xl font-bold mb-8 text-yellow-500">Выберите свой клуб!</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
            <!-- Заглушка для карточек клубов -->
            <div class="p-8 border border-gray-700 rounded hover:border-yellow-500 transition cursor-pointer">
                <h2 class="text-2xl font-bold"><a href="http://piter.extra.new">Клуб в Питере</a></h2>
            </div>
            <div class="p-8 border border-gray-700 rounded hover:border-yellow-500 transition cursor-pointer">
                <h2 class="text-2xl font-bold"><a href="http://matros.extra.new">Клуб Матрос</a></h2>
            </div>
            <div class="p-8 border border-gray-700 rounded hover:border-yellow-500 transition cursor-pointer">
                <h2 class="text-2xl font-bold"><a href="http://de-vision.new">Клуб De-Vision</a></h2>
                <p class="text-gray-400 mt-2">Москва</p>
            </div>
        </div>
    </div>
</div>
@endsection

