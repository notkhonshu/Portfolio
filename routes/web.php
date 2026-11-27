<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

Route::get('/projects/{slug}', fn (string $slug) => redirect()->route('projects.show', $slug, 301))
    ->where('slug', '[a-z0-9-]+');

Route::controller(MainController::class)->group(function () {
    Route::get('/', 'index')->name('home');

    Route::get('/sitemap.xml', 'sitemap')->name('sitemap');

    Route::get('/{slug}', 'project')
        ->where('slug', '[a-z0-9-]+')
        ->name('projects.show');
});