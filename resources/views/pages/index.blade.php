@extends('layouts.app')

@section('content')
    @include('components.sections.part1', [
        'section_data' => [
            'data' => config('data.project', []),
        ],
    ])

    @include('components.sections.part2')

    @include('components.sections.part3', [
        'section_data' => [
            'label' => 'About',
            'name' => config('socials.names'),
            'headline' => 'A frontend web developer building fast, considered websites for hospitality and travel brands.',
            'intro' => 'I’m Angga Aditya, a frontend web developer working with hotels, resorts, spas and tour operators in Bali. I turn designs into clean, responsive and accessible interfaces that make people want to book, visit and come back.',
            'paragraphs' => [
                'I started building websites because I enjoy seeing an idea turn into something people can actually use. Over time I focused on the frontend, where structure, typography and motion come together to shape how a brand feels online.',
                'I care about details that are easy to miss: spacing that feels right, pages that load quickly, layouts that hold up on every screen, and code that the next developer will be glad to open.',
            ],
            'photo' => 'assets/img/static/landing/my-foto.jpeg',
            'photo_alt' => 'Portrait of Angga',
            'facts' => [
                [
                    'label' => 'Role', 
                    'value' => 'Frontend Web Developer'
                ],
                [
                    'label' => 'Based in', 
                    'value' => 'Bali, Indonesia'
                ],
                [
                    'label' => 'Focus', 
                    'value' => 'Hospitality, spa & travel websites'
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
            'stack' => 
                [
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
                    'role' => 'Internship Frontend Web Developer', 
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