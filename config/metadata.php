<?php

$socials = require __DIR__ . '/socials.php';

$name = $socials['names'];
$url  = rtrim(env('APP_URL', ''), '/');

return [
    'site_name'      => $name . ' — Web Developer in Bali',
    'alternate_name' => [$name . ' Portfolio', 'Bali Web Developer'],

    'title'       => $name . ' — Web Developer in Bali | Custom Website Development',
    'description' => 'Web developer in Bali building fast, responsive, SEO-friendly websites for businesses and brands. Company sites, landing pages, online stores, WordPress & Laravel.',
    'keywords'    => 'web developer bali, website developer bali, website development bali, web designer bali, freelance web developer bali, custom website development, company website, landing page developer, laravel developer, wordpress developer',
    'author'      => $name,
    'job_title'   => 'Web Developer',
    'url'         => $url,
    'locale'      => 'en_US',
    'locale_alt'  => 'id_ID',
    'robots'      => 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
    'theme_color' => '#355C9A',

    'og_type'         => 'website',
    'og_image'        => 'assets/img/metadata/og-image.png',
    'og_image_alt'    => $name . ' — Web Developer in Bali',
    'og_image_width'  => 1200,
    'og_image_height' => 630,

    'twitter_card' => 'summary_large_image',
    'twitter'      => '',

    'same_as' => array_values(array_filter([
        $socials['instagram']['url'] ?? null,
        $socials['github']['url'] ?? null,
        $socials['linkedin']['url'] ?? null,
    ])),

    'photo'        => 'assets/img/metadata/og-image.png',
    'locality'     => 'Denpasar',
    'region'       => 'Bali',
    'country'      => 'ID',
    'geo'          => ['lat' => -8.6705, 'lng' => 115.2126],
    'price_range'  => '$$',
    'knows_about'  => ['Web development', 'Website design', 'Laravel', 'Blade', 'Tailwind CSS', 'JavaScript', 'GSAP', 'WordPress', 'Elementor'],
];