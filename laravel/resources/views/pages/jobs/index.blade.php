@extends('layouts.app')
@section('title', 'Вакансии клуба ' . ($club->title ?? 'EXTRASPORT'))

@section('content')

@include('layouts.parts.breadcrumbs', [
    'items' => [
        ['label' => $club->title ?? 'EXTRASPORT', 'url' => route('home')],
        ['label' => 'Вакансии'],
    ]
])

<section class="max-w-7xl mx-auto px-4 pt-10 pb-16">

    <header class="mb-10">
        <h1 class="font-heading font-bold text-4xl md:text-5xl uppercase tracking-tight text-white">Вакансии</h1>
        <p class="mt-2 text-white/40 text-sm font-heading uppercase tracking-widest">{{ $club->title ?? 'EXTRASPORT' }}</p>
    </header>

    @if($jobs->isEmpty())
        <div class="py-20 text-center border border-white/8">
            <i class="fa-regular fa-briefcase text-4xl text-white/20 mb-4 block"></i>
            <p class="font-heading uppercase tracking-widest text-sm text-white/30">Открытых вакансий нет</p>
            <p class="text-white/20 text-sm mt-2">Следите за обновлениями</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($jobs as $job)
            <div class="group flex items-center justify-between gap-6 p-6 border-l-2 border-white/10 hover:border-[var(--color-primary)] bg-white/2 hover:bg-white/4 transition-all duration-200 cursor-pointer"
                 onclick="document.getElementById('job-modal-{{ $job->id }}').classList.remove('hidden')">
                <div>
                    <h3 class="font-heading font-bold text-lg uppercase tracking-wide text-white group-hover:text-[var(--color-primary)] transition-colors">
                        {{ $job->title }}
                    </h3>
                    <p class="text-xs font-heading uppercase tracking-widest text-white/30 mt-1">Полная занятость · Сменный график</p>
                </div>
                <div class="shrink-0 flex items-center gap-3">
                    <span class="hidden sm:inline text-xs font-heading uppercase tracking-widest text-[var(--color-primary)] opacity-0 group-hover:opacity-100 transition-opacity">
                        Подробнее
                    </span>
                    <div class="w-9 h-9 border border-white/15 group-hover:border-[var(--color-primary)] group-hover:bg-[var(--color-primary)] flex items-center justify-center transition-all duration-200">
                        <i class="fa-solid fa-arrow-right text-xs text-white/40 group-hover:text-black transition-colors"></i>
                    </div>
                </div>
            </div>

            {{-- Job modal --}}
            <div id="job-modal-{{ $job->id }}"
                 class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/85 backdrop-blur-sm px-4"
                 onclick="if(event.target===this)this.classList.add('hidden')">
                <div class="relative bg-gray-950 border border-white/10 max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="sticky top-0 bg-gray-950 border-b border-white/8 px-8 py-5 flex items-center justify-between">
                        <div>
                            <h3 class="font-heading font-bold text-xl uppercase tracking-wide text-white">{{ $job->title }}</h3>
                            <p class="text-xs text-white/30 font-heading uppercase tracking-widest mt-0.5">{{ $club->title ?? 'EXTRASPORT' }}</p>
                        </div>
                        <button onclick="document.getElementById('job-modal-{{ $job->id }}').classList.add('hidden')"
                                class="w-8 h-8 border border-white/15 hover:border-white/40 flex items-center justify-center text-white/40 hover:text-white transition-colors text-lg leading-none shrink-0">
                            &times;
                        </button>
                    </div>
                    <div class="px-8 py-6 prose prose-invert prose-sm max-w-none text-white/70">
                        {!! $job->content !!}
                    </div>
                    <div class="px-8 pb-8">
                        <button onclick="document.getElementById('job-modal-{{ $job->id }}').classList.add('hidden'); document.getElementById('job-apply-modal').classList.remove('hidden')"
                                class="inline-flex items-center gap-2 border border-[var(--color-primary)] text-[var(--color-primary)] hover:bg-[var(--color-primary)] hover:text-black font-heading font-bold uppercase text-sm tracking-widest px-6 py-3 transition-colors">
                            Откликнуться <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

</section>

{{-- Apply modal --}}
<div id="job-apply-modal"
     class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/85 backdrop-blur-sm px-4"
     onclick="if(event.target===this)this.classList.add('hidden')">
    <div class="relative bg-gray-950 border border-white/10 max-w-md w-full">
        <div class="border-b border-white/8 px-8 py-5 flex items-center justify-between">
            <h3 class="font-heading font-bold text-xl uppercase tracking-wide text-white">Откликнуться</h3>
            <button onclick="document.getElementById('job-apply-modal').classList.add('hidden')"
                    class="w-8 h-8 border border-white/15 hover:border-white/40 flex items-center justify-center text-white/40 hover:text-white transition-colors text-lg leading-none">
                &times;
            </button>
        </div>
        <form action="{{ url('/job/subscribe') }}" method="POST" enctype="multipart/form-data" class="px-8 py-6 space-y-4">
            @csrf
            <p class="text-xs text-white/30 font-heading uppercase tracking-widest -mt-1 mb-5">Пожалуйста, заполните форму — мы свяжемся с вами</p>
            <input type="text" name="name" placeholder="Ваше имя *"
                   class="w-full bg-black border border-white/15 text-white placeholder-white/25 px-4 py-3 text-sm focus:outline-none focus:border-[var(--color-primary)] transition-colors">
            <input type="tel" name="tel" placeholder="Ваш телефон *"
                   class="w-full bg-black border border-white/15 text-white placeholder-white/25 px-4 py-3 text-sm focus:outline-none focus:border-[var(--color-primary)] transition-colors">
            <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-heading uppercase tracking-widest border border-white/15 hover:border-[var(--color-primary)] text-white/40 hover:text-[var(--color-primary)] px-4 py-2.5 transition-colors">
                <i class="fa-regular fa-paperclip"></i> Прикрепить резюме *
                <input type="file" name="rezume" id="rezume-input" accept=".pdf,.docx" class="sr-only">
            </label>
            <p id="rezume-name" class="text-xs text-white/25 -mt-2">*.pdf или *.docx, до 100 Кб</p>
            <label class="flex items-start gap-2 text-xs text-white/40 cursor-pointer">
                <input type="checkbox" name="accept" class="mt-0.5 accent-[var(--color-primary)]">
                <span>Ознакомлен с <a href="{{ url('/privacy/') }}" target="_blank" class="text-[var(--color-primary)] hover:underline">политикой конфиденциальности</a></span>
            </label>
            <button type="submit"
                    class="w-full bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] text-black font-heading font-bold uppercase text-sm tracking-widest py-3.5 transition-colors">
                Отправить заявку
            </button>
            <input type="hidden" name="title" id="job-title-input">
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('rezume-input').addEventListener('change', function () {
    document.getElementById('rezume-name').textContent = this.files[0]?.name || '*.pdf или *.docx, до 100 Кб';
});
</script>
@endpush

@include('layouts.parts.subscribe')
@endsection
