<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', config('metadata.title'))</title>
<meta name="description" content="@yield('description', config('metadata.description'))">
<meta name="keywords" content="@yield('keywords', config('metadata.keywords'))">
<meta name="author" content="{{ config('metadata.author') }}">
<meta name="robots" content="{{ config('metadata.robots') }}">
<meta name="theme-color" content="{{ config('metadata.theme_color') }}">
<link rel="canonical" href="@yield('canonical', url()->current())">

{{-- Open Graph --}}
<meta property="og:type" content="{{ config('metadata.og_type', 'website') }}">
<meta property="og:site_name" content="{{ config('metadata.site_name') }}">
<meta property="og:title" content="@yield('title', config('metadata.title'))">
<meta property="og:description" content="@yield('description', config('metadata.description'))">
<meta property="og:url" content="@yield('canonical', url()->current())">
<meta property="og:image" content="@yield('og_image', asset(config('metadata.og_image')))">
<meta property="og:image:alt" content="@yield('og_image_alt', config('metadata.og_image_alt'))">
@sectionMissing('og_image')
    <meta property="og:image:width" content="{{ config('metadata.og_image_width', 1200) }}">
    <meta property="og:image:height" content="{{ config('metadata.og_image_height', 630) }}">
@endif
<meta property="og:locale" content="{{ config('metadata.locale') }}">
<meta property="og:locale:alternate" content="{{ config('metadata.locale_alt') }}">

{{-- Twitter / X --}}
<meta name="twitter:card" content="{{ config('metadata.twitter_card', 'summary_large_image') }}">
@if (config('metadata.twitter'))
    <meta name="twitter:site" content="{{ config('metadata.twitter') }}">
@endif
<meta name="twitter:title" content="@yield('title', config('metadata.title'))">
<meta name="twitter:description" content="@yield('description', config('metadata.description'))">
<meta name="twitter:image" content="@yield('og_image', asset(config('metadata.og_image')))">
<meta name="twitter:image:alt" content="@yield('og_image_alt', config('metadata.og_image_alt'))">

{{-- Favicon --}}
<link rel="icon" href="{{ asset('assets/img/metadata/favicon/icon.ico') }}">

{{-- Vite --}}
@vite(['resources/css/app.css', 'resources/js/app.js'])

{{-- GSAP --}}
<script src="https://cdn.jsdelivr.net/npm/gsap@3/dist/gsap.min.js" defer></script>

{{-- Structured data (JSON-LD) --}}
@php
    $meta = config('metadata');
    $home = url('/');

    $address = [
        '@type'           => 'PostalAddress',
        'addressLocality' => $meta['locality'],
        'addressRegion'   => $meta['region'],
        'addressCountry'  => $meta['country'],
    ];

    $schema = [
        '@context' => 'https://schema.org',
        '@graph'   => [
            [
                '@type'      => 'WebSite',
                '@id'        => $home . '#website',
                'url'        => $home,
                'name'       => $meta['site_name'],
                'inLanguage' => 'en',
                'publisher'  => ['@id' => $home . '#business'],
            ],
            [
                '@type'       => ['ProfessionalService', 'LocalBusiness'],
                '@id'         => $home . '#business',
                'name'        => $meta['site_name'],
                'description' => $meta['description'],
                'url'         => $home,
                'image'       => asset($meta['og_image']),
                'priceRange'  => $meta['price_range'],
                'address'     => $address,
                'geo'         => [
                    '@type'     => 'GeoCoordinates',
                    'latitude'  => $meta['geo']['lat'],
                    'longitude' => $meta['geo']['lng'],
                ],
                'areaServed'  => [
                    ['@type' => 'AdministrativeArea', 'name' => 'Bali'],
                    ['@type' => 'Country', 'name' => 'Indonesia'],
                ],
                'knowsAbout'  => $meta['knows_about'],
                'founder'     => ['@id' => $home . '#person'],
                'sameAs'      => $meta['same_as'],
            ],
            [
                '@type'       => 'Person',
                '@id'         => $home . '#person',
                'name'        => $meta['author'],
                'jobTitle'    => $meta['job_title'],
                'description' => $meta['description'],
                'url'         => $home,
                'image'       => asset($meta['photo']),
                'worksFor'    => ['@id' => $home . '#business'],
                'address'     => $address,
                'knowsAbout'  => $meta['knows_about'],
                'sameAs'      => $meta['same_as'],
            ],
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>

@stack('head')
</head>