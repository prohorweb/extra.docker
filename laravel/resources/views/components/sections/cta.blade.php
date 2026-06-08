{{--
@component x-sections.cta
@prop string $heading
@prop string $description
@prop array $actions — массив [['text' => '...', 'variant' => 'brand', 'url' => '...']]
--}}

<section class="py-20 bg-gradient-to-r from-gray-900 to-gray-800">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-4xl font-bold mb-4">{{ $heading }}</h2>
        @if(isset($description))
            <p class="text-gray-400 mb-8 max-w-2xl mx-auto">{{ $description }}</p>
        @endif
        @if(isset($actions))
            <div class="flex justify-center gap-4 flex-wrap">
                @foreach($actions as $action)
                    <x-ui.button :variant="$action['variant'] ?? 'brand'" :href="$action['url'] ?? '#'">{{ $action['text'] }}</x-ui.button>
                @endforeach
            </div>
        @endif
    </div>
</section>