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
<link rel="alternate" hreflang="en" href="@yield('canonical', url()->current())">
<link rel="alternate" hreflang="x-default" href="@yield('canonical', url()->current())">

{{-- Open Graph --}}
<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ config('metadata.site_name') }}">
<meta property="og:title" content="@yield('title', config('metadata.title'))">
<meta property="og:description" content="@yield('description', config('metadata.description'))">
<meta property="og:url" content="@yield('canonical', url()->current())">
<meta property="og:image" content="{{ url(config('metadata.og_image')) }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:locale" content="{{ config('metadata.locale') }}">
<meta property="og:locale:alternate" content="{{ config('metadata.locale_alt') }}">

{{-- Twitter / X --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="{{ config('metadata.twitter') }}">
<meta name="twitter:title" content="@yield('title', config('metadata.title'))">
<meta name="twitter:description" content="@yield('description', config('metadata.description'))">
<meta name="twitter:image" content="{{ url(config('metadata.og_image')) }}">

{{-- ===== GEO (ditulis langsung; sesuaikan koordinat dengan lokasi usahamu) ===== --}}
<meta name="geo.region" content="ID-BA">
<meta name="geo.placename" content="Denpasar, Bali">
<meta name="geo.position" content="-8.6705;115.2126">
<meta name="ICBM" content="-8.6705, 115.2126">
<meta name="content-language" content="en, id">

{{-- Favicon --}}
<link rel="icon" href="{{ asset('assets/img/metadata/favicon/icon.ico') }}">

{{-- Vite --}}
@vite(['resources/css/app.css', 'resources/js/app.js'])

{{-- GSAP --}}
<script src="https://cdn.jsdelivr.net/npm/gsap@3/dist/gsap.min.js"></script>

{{-- Structured data (JSON-LD) untuk mesin pencari & AI/GEO --}}
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@graph": [
    {
      "@@type": "WebSite",
      "@@id": "{{ url('/') }}#website",
      "url": "{{ url('/') }}",
      "name": "{{ config('metadata.site_name') }}",
      "inLanguage": ["en", "id"],
      "publisher": { "@@id": "{{ url('/') }}#business" }
    },
    {
      "@@type": ["ProfessionalService", "LocalBusiness"],
      "@@id": "{{ url('/') }}#business",
      "name": "Angga — Web Developer & Designer",
      "description": "Freelance web developer and designer building websites for hotels, resorts, tour operators and local brands.",
      "url": "{{ url('/') }}",
      "image": "{{ url(config('metadata.og_image')) }}",
      "priceRange": "$$",
      "address": {
        "@@type": "PostalAddress",
        "addressLocality": "Denpasar",
        "addressRegion": "Bali",
        "addressCountry": "ID"
      },
      "geo": {
        "@@type": "GeoCoordinates",
        "latitude": -8.6705,
        "longitude": 115.2126
      },
      "areaServed": [
        { "@@type": "AdministrativeArea", "name": "Bali" },
        { "@@type": "Country", "name": "Indonesia" }
      ],
      "knowsAbout": ["Web design", "Frontend development", "Laravel", "Next.js", "WordPress", "Elementor", "GSAP"],
      "founder": { "@@id": "{{ url('/') }}#person" },
      "sameAs": {!! json_encode(config('metadata.same_as'), JSON_UNESCAPED_SLASHES) !!}
    },
    {
      "@@type": "Person",
      "@@id": "{{ url('/') }}#person",
      "name": "Angga",
      "jobTitle": "Web Developer & Designer",
      "url": "{{ url('/') }}",
      "worksFor": { "@@id": "{{ url('/') }}#business" },
      "address": {
        "@@type": "PostalAddress",
        "addressLocality": "Denpasar",
        "addressRegion": "Bali",
        "addressCountry": "ID"
      },
      "sameAs": {!! json_encode(config('metadata.same_as'), JSON_UNESCAPED_SLASHES) !!}
    }
  ]
}
</script>

@stack('head')
</head>