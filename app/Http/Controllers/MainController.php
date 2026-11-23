<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Carbon\Carbon;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class MainController extends Controller
{
    public function index(): View
    {
        return $this->page();
    }

    public function project(string $slug): View
    {
        $project = $this->projects()->firstWhere('slug', $slug);

        abort_unless($project, 404);

        return $this->page($project);
    }

    public function sitemap(): Response
    {
        $lastmod = Carbon::createFromTimestamp(filemtime(config_path('data.php')));

        $sitemap = Sitemap::create()
            ->add(Url::create(url('/'))->setLastModificationDate($lastmod));

        foreach ($this->projects() as $project) {
            $url = Url::create(route('projects.show', $project['slug']))
                ->setLastModificationDate($lastmod)
                ->addImage(asset($project['feature_images']), '', '', $project['title']);

            foreach ($project['gallery'] ?? [] as $i => $image) {
                $url->addImage(asset($image), '', '', $project['title'] . ' — ' . ($i + 1));
            }

            $sitemap->add($url);
        }

        return response($sitemap->render(), 200, [
            'Content-Type'  => 'application/xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    private function page(?array $project = null): View
    {
        return view('pages.index', [
            'activeProject' => $project,
            'seo'           => $project ? $this->projectSeo($project) : [],
            'projectTitles' => $this->projects()->mapWithKeys(
                fn (array $item) => [$item['slug'] => $this->projectTitle($item)]
            ),
        ]);
    }

    private function projects(): Collection
    {
        return collect(config('data.project', []));
    }

    private function projectTitle(array $project): string
    {
        return "{$project['title']} — {$project['project_type']} | " . config('metadata.author');
    }

    private function projectSeo(array $project): array
    {
        $home     = url('/');
        $url      = route('projects.show', $project['slug']);
        $place    = $project['location'] . ', Bali';
        $title    = $this->projectTitle($project);
        $image    = asset($project['feature_images']);
        $gallery  = array_map('asset', $project['gallery'] ?? []);
        $keywords = implode(', ', [
            $project['title'],
            $project['project_type'],
            "web developer {$place}",
            $project['stack'],
        ]);

        $description = $this->excerpt("{$project['project_type']} in {$place}. {$project['overview']}");

        return [
            'title'       => $title,
            'description' => $description,
            'keywords'    => $keywords,
            'canonical'   => $url,
            'image'       => $image,
            'image_alt'   => "{$project['title']} website, designed and developed by " . config('metadata.author'),
            'schema'      => [
                '@context' => 'https://schema.org',
                '@graph'   => [
                    [
                        '@type'              => 'WebPage',
                        '@id'                => $url . '#webpage',
                        'url'                => $url,
                        'name'               => $title,
                        'description'        => $description,
                        'isPartOf'           => ['@id' => $home . '#website'],
                        'breadcrumb'         => ['@id' => $url . '#breadcrumb'],
                        'primaryImageOfPage' => ['@type' => 'ImageObject', 'url' => $image],
                        'mainEntity'         => ['@id' => $url . '#project'],
                    ],
                    [
                        '@type'           => 'CreativeWork',
                        '@id'             => $url . '#project',
                        'name'            => $project['title'],
                        'description'     => $project['overview'],
                        'genre'           => $project['project_type'],
                        'image'           => array_merge([$image], $gallery),
                        'creator'         => ['@id' => $home . '#person'],
                        'dateCreated'     => $project['years'],
                        'keywords'        => $keywords,
                        'contentLocation' => ['@type' => 'Place', 'name' => $place],
                        'sameAs'          => $project['link'] ?? null,
                    ],
                    [
                        '@type'           => 'BreadcrumbList',
                        '@id'             => $url . '#breadcrumb',
                        'itemListElement' => [
                            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $home],
                            ['@type' => 'ListItem', 'position' => 2, 'name' => $project['title'], 'item' => $url],
                        ],
                    ],
                ],
            ],
        ];
    }

    private function excerpt(string $text, int $max = 158): string
    {
        $text = trim(preg_replace('/\s+/', ' ', $text));

        if (mb_strlen($text) <= $max) {
            return $text;
        }

        $cut = mb_substr($text, 0, $max);
        $pos = mb_strrpos($cut, ' ');

        return rtrim(mb_substr($cut, 0, $pos ?: $max), " ,.;:") . '…';
    }
}