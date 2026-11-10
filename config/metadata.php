<?php

$socials = require __DIR__ . '/socials.php';

return [
    'site_name'   => config('app.name'),
    'title'       => config('app.name') . ' of ' . $socials['names'] . ' — Web Developer & Designer',
    'description' => 'Frontend web developer and designer crafting distinctive, fast websites for hotels, resorts, tour operators and local brands in Bali.',
    'keywords'    => 'web developer bali, web designer bali, frontend web developer, website hotel, website resort, website tour operator, laravel developer, wordpress elementor, jasa pembuatan website bali',
    'author'      => $socials['names'],
    'url'         => env('APP_URL', ''),
    'locale'      => 'en_US',
    'locale_alt'  => 'id_ID',
    'robots'      => 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
    'theme_color' => '#355C9A',
    'og_image'    => 'assets/img/metadata/f_images/1.png',
    'twitter'     => '',
    'same_as'     => array_values(array_filter([
        $socials['instagram']['url'] ?? null,
        $socials['github']['url'] ?? null,
    ])),
];