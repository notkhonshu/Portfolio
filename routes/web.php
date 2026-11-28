<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/clear-cache', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');

    return "✅ All cache (config, route, view, app) has been cleared.";
});

Route::get('/projects/{slug}', fn (string $slug) => redirect()->route('projects.show', $slug, 301))
    ->where('slug', '[a-z0-9-]+');

Route::controller(MainController::class)->group(function () {
    Route::get('/', 'index')->name('home');

    Route::get('/sitemap.xml', 'sitemap')->name('sitemap');

    Route::get('/about-me', 'about')->name('about');

    Route::get('/{slug}', 'project')
        ->where('slug', '[a-z0-9-]+')
        ->name('projects.show');
});