{{--
@component x-seo.meta
@prop SEOData|null $data — SEO-данные (title, description, canonical, og)
--}}

<meta name="description" content="{{ $data->description ?? 'Extra Fitness — сеть фитнес-клубов' }}">
<meta property="og:title" content="{{ $data->title ?? 'Extra Fitness' }}">
<meta property="og:description" content="{{ $data->description ?? 'Сеть фитнес-клубов на результат!' }}">
<meta property="og:type" content="website">
@if($data->canonical ?? false)
    <link rel="canonical" href="{{ $data->canonical }}">
@endif
<title>{{ $data->title ?? 'Extra Fitness' }}</title>