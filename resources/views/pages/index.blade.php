@extends('layouts.app')

@section('title', $seo['title'] ?? config('metadata.title'))
@section('description', $seo['description'] ?? config('metadata.description'))

@isset($seo['canonical'])
    @section('canonical', $seo['canonical'])
    @section('keywords', $seo['keywords'])
    @section('og_image', $seo['image'])
    @section('og_image_alt', $seo['image_alt'])

    @push('head')
        <script type="application/ld+json">{!! json_encode($seo['schema'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
    @endpush
@endisset

@section('content')
    @include('components.sections.part1', [
        'section_data' => [
            'data' => config('data.project', []),
        ],
    ])

    @include('components.sections.part2')

    @include('components.sections.part3', [
        'section_data' => [
            'page_title' => $aboutTitle,
            'label' => 'About',
            'name' => config('socials.names'),
            'headline' => 'A web developer building fast, thoughtful websites for businesses that want to be found and remembered.',
            'intro' => 'I’m Angga Aditya, a web developer based in Bali. I build clean, responsive and accessible websites for brands, businesses and agencies, from company profiles and landing pages to booking and e-commerce experiences.',
            'paragraphs' => [
                'I started building websites because I enjoy seeing an idea turn into something people can actually use. Over time I focused on the frontend, where structure, typography and motion come together to shape how a brand feels online.',
                'I care about details that are easy to miss: spacing that feels right, pages that load quickly, layouts that hold up on every screen, and code that the next developer will be glad to open.',
            ],
            'photo' => 'assets/img/static/landing/my-foto.jpeg',
            'photo_alt' => 'Angga Aditya, web developer based in Bali',
            'facts' => [
                [
                    'label' => 'Role',
                    'value' => 'Web Developer'
                ],
                [
                    'label' => 'Based in',
                    'value' => 'Bali, Indonesia'
                ],
                [
                    'label' => 'Focus',
                    'value' => 'Business websites, landing pages & web apps'
                ],
            ],
            'services_label' => 'What I do',
            'services' => [
                [
                    'title' => 'UI development',
                    'text' => 'Pixel-accurate, responsive interfaces built from design files with semantic and accessible markup.'
                ],
                [
                    'title' => 'Laravel & Blade',
                    'text' => 'Reusable Blade components styled with Tailwind CSS and powered by a clean, maintainable structure.'
                ],
                [
                    'title' => 'WordPress & Elementor',
                    'text' => 'Custom sections and layouts that stay easy for content teams to edit.'
                ],
                [
                    'title' => 'Motion & interaction',
                    'text' => 'Scroll and hover animations with GSAP and vanilla JavaScript that add personality without slowing the page down.'
                ],
            ],
            'stack_label' => 'Stack',
            'stack' => [
                'Laravel',
                'Blade',
                'Tailwind CSS',
                'JavaScript',
                'GSAP',
                'Vite',
                'WordPress',
                'Elementor'
            ],
            'experience_label' => 'Experience',
            'experience' => [
                [
                    'years' => '2025 - Now',
                    'role' => 'Frontend Web Developer',
                    'company' => 'PT. Kirana Bali Wisata'
                ],
                [
                    'years' => '2024 — 2025',
                    'role' => 'Internship Web Developer',
                    'company' => 'PT. Bali Pustaka Digital'
                ],
            ],
            'cta' => [
                'text' => 'Let’s build something worth visiting.',
                'label' => 'Let’s talk',
                'href' => config('socials.contact.url_email'),
            ],
        ],
    ])
@endsection