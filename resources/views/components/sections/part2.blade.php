@php
    $projects = config('data.project', []);
    $activeSlug = $activeProject['slug'] ?? null;
    $meta = [
        'Client' => 'client',
        'Type' => 'project_type',
        'Location' => 'location',
        'Year' => 'years',
        'Role' => 'role',
        'Stack' => 'stack',
    ];
@endphp

<div data-detail data-open="{{ $activeSlug ? 'true' : 'false' }}" data-home-title="{{ config('metadata.title') }}" @unless ($activeSlug) inert @endunless class="group/detail pointer-events-none fixed inset-0 z-(--z-panel) data-[open=true]:pointer-events-auto">
    <div data-detail-backdrop class="absolute inset-0 bg-brand-900/60 opacity-0 transition-opacity duration-(--duration-slow) ease-smooth group-data-[open=true]/detail:opacity-100"></div>

    <div data-detail-panel data-full="false" role="dialog" aria-modal="true" aria-label="Project detail" tabindex="-1" class="panel absolute inset-x-0 bottom-0 top-[clamp(3rem,9svh,6rem)] flex translate-y-full flex-col overflow-hidden bg-panel-alt outline-none transition-[translate,top,border-radius] duration-(--duration-slow) ease-smooth group-data-[open=true]/detail:translate-y-0 data-[full=true]:top-0 data-[full=true]:rounded-t-none">
        <div class="flex shrink-0 items-center justify-between border-b border-panel-line px-gutter py-5">
            <span data-detail-counter class="label text-panel-foreground/70"></span>
            <button type="button" data-detail-close class="btn text-panel-foreground">Close</button>
        </div>

        <div data-detail-scroll class="min-h-0 flex-1 overflow-y-auto overscroll-contain">
            @foreach ($projects as $project)
                <article data-detail-item="{{ $project['slug'] }}" data-detail-title="{{ $projectTitles[$project['slug']] ?? '' }}" @if ($project['slug'] !== $activeSlug) hidden @endif class="mx-auto max-w-site px-gutter pb-section pt-8">
                    <h2 class="font-display text-5xl font-extrabold">{{ $project['title'] }}</h2>
                    <dl class="mt-8 grid grid-cols-2 gap-6 border-t border-panel-line pt-5 sm:grid-cols-3 lg:grid-cols-6">
                        @foreach ($meta as $label => $key)
                            <div>
                                <dt class="label text-panel-foreground/70">{{ $label }}</dt>
                                <dd class="mt-2 text-base">{{ $project[$key] }}</dd>
                            </div>
                        @endforeach
                    </dl>
                    <img src="{{ asset($project['feature_images']) }}" alt="{{ $project['title'] }}" loading="lazy" decoding="async" class="mt-10 aspect-16/8 w-full rounded-card object-cover object-center">
                    <div class="mt-12 grid gap-8 sm:grid-cols-2">
                        <div>
                            <h3 class="label mb-3 text-panel-foreground/70">Overview</h3>
                            <p class="font-display text-xl font-extrabold">{{ $project['overview'] }}</p>
                        </div>
                        <div>
                            <h3 class="label mb-3 text-panel-foreground/70">Challenge &amp; Result</h3>
                            <p class="text-base">{{ $project['result'] }}</p>
                        </div>
                    </div>

                    <div data-gallery class="mt-12 grid gap-6 sm:grid-cols-2">
                        @foreach ($project['gallery'] as $image)
                            <button type="button" data-gallery-item data-src="{{ asset($image) }}" data-alt="{{ $project['title'] }} — {{ $loop->iteration }}" aria-label="View image {{ $loop->iteration }} of {{ $loop->count }}" class="group/img block cursor-zoom-in overflow-hidden rounded-card">
                                <img src="{{ asset($image) }}" alt="{{ $project['title'] }} — {{ $loop->iteration }}" loading="lazy" decoding="async" class="aspect-4/3 w-full object-cover object-top transition-transform duration-(--duration-slow) ease-smooth group-hover/img:scale-105">
                            </button>
                        @endforeach
                    </div>

                    <div class="mt-12 flex flex-wrap gap-3">
                        <a href="{{ $project['link'] }}" target="_blank" rel="noopener noreferrer" class="btn border-brand-500 bg-brand-500 text-white">Visit site →</a>
                        <button type="button" data-detail-next class="btn text-panel-foreground">Next project →</button>
                    </div>
                </article>
            @endforeach
        </div>
    </div>

    <div data-lightbox data-open="false" role="dialog" aria-modal="true" aria-label="Gallery image" class="absolute inset-0 z-99 hidden items-center justify-center p-4 data-[open=true]:flex sm:p-10">
        <div data-lightbox-backdrop class="absolute inset-0 bg-brand-900/90"></div>

        <button type="button" data-lightbox-close class="btn absolute right-4 top-4 z-20 text-white">Close</button>

        <div class="pointer-events-none relative z-10 flex max-h-full w-full items-center justify-center gap-3 sm:gap-6">
            <button type="button" data-lightbox-prev aria-label="Previous image" class="btn pointer-events-auto shrink-0 text-white">←</button>

            <figure class="flex min-w-0 flex-col items-center">
                <img data-lightbox-img alt="" class="pointer-events-auto max-h-[80svh] max-w-full rounded-card object-contain">
                <figcaption data-lightbox-counter class="label mt-4 text-white/70"></figcaption>
            </figure>

            <button type="button" data-lightbox-next aria-label="Next image" class="btn pointer-events-auto shrink-0 text-white">→</button>
        </div>
    </div>
</div>