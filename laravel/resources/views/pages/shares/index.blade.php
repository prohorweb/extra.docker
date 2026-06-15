@extends('layouts.app')
@section('title', 'Акции клуба ' . ($club->title ?? 'EXTRASPORT'))

@push('head')
<style>
#shares-parallax-bg {
    position: fixed;
    inset: 0;
    background: url('/uploads/layout/img/actions-bg.jpg') center/cover no-repeat;
    filter: blur(14px);
    opacity: 0.55;
    transform: scale(1.12);
    will-change: transform;
    pointer-events: none;
    z-index: -2;
}
#shares-overlay {
    position: fixed;
    inset: 0;
    background: linear-gradient(135deg, rgba(0,0,0,0.65) 0%, rgba(0,0,0,0.40) 100%);
    pointer-events: none;
    z-index: -1;
}
</style>
@endpush

@section('content')
<div id="shares-page">
<div id="shares-parallax-bg" aria-hidden="true"></div>
<div id="shares-overlay" aria-hidden="true"></div>

@include('layouts.parts.breadcrumbs', [
    'items' => [
        ['label' => $club->title ?? 'EXTRASPORT', 'url' => route('home')],
        ['label' => 'Акции'],
    ]
])

<section class="max-w-7xl mx-auto px-4 pt-10 pb-16">

    <header class="mb-10">
        <h1 class="font-heading font-bold text-4xl md:text-5xl uppercase tracking-tight text-white">
            Акции <span class="text-[var(--color-primary)]">&</span> предложения
        </h1>
        <p class="mt-2 text-white/40 text-sm font-heading uppercase tracking-widest">{{ $club->title ?? 'EXTRASPORT' }}</p>
    </header>

    @php $items = $shares; @endphp

    @if($items->isEmpty())
        <p class="text-white/40 text-center py-20 font-heading uppercase tracking-widest">Акций не найдено</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            @foreach($items as $item)
                <x-ui.card.share :item="$item" />
            @endforeach
        </div>
        <x-ui.pagination :paginator="$items" />
    @endif

</section>

@include('layouts.parts.subscribe')
</div>

@push('scripts')
<script>
(function () {
    var bg = document.getElementById('shares-parallax-bg');
    if (!bg) return;
    var raf = null;
    function update() {
        bg.style.transform = 'scale(1.12) translateY(' + (window.scrollY * 0.25) + 'px)';
        raf = null;
    }
    window.addEventListener('scroll', function () {
        if (!raf) raf = requestAnimationFrame(update);
    }, { passive: true });
    update();
}());
</script>
@endpush

@endsection
