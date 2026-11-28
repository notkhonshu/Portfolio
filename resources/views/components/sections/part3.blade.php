<div data-about
     data-open="false"
     data-home-title="{{ config('metadata.title') }}"
     data-about-title="{{ $section_data['page_title'] ?? '' }}"
     inert
     class="group/about pointer-events-none fixed inset-0 z-(--z-panel) data-[open=true]:pointer-events-auto">
    <div data-about-backdrop class="absolute inset-0 bg-brand-900/60 opacity-0 transition-opacity duration-(--duration-slow) ease-smooth group-data-[open=true]/about:opacity-100"></div>

    <div id="aboutPanel"
         data-about-panel
         data-full="false"
         role="dialog"
         aria-modal="true"
         aria-label="{{ $section_data['label'] ?? 'About' }}"
         tabindex="-1"
         class="panel absolute inset-x-0 bottom-0 top-[clamp(3rem,9svh,6rem)] flex translate-y-full flex-col overflow-hidden outline-none transition-[translate,top,border-radius] duration-(--duration-slow) ease-smooth group-data-[open=true]/about:translate-y-0 data-[full=true]:top-0 data-[full=true]:rounded-t-none">
        <div class="flex shrink-0 items-center justify-between border-b border-panel-line px-gutter py-5">
            <span class="label text-panel-foreground/70">({{ $section_data['label'] ?? 'About' }})</span>
            <button type="button" data-about-close aria-label="Close about panel" class="btn text-panel-foreground">Close</button>
        </div>

        <div data-about-scroll class="min-h-0 flex-1 overflow-y-auto overscroll-contain">
            <div class="mx-auto max-w-site px-gutter pb-section pt-8">
                <h2 id="aboutHeading" class="max-w-[20ch] font-display text-4xl font-extrabold">
                    {{ $section_data['headline'] ?? '' }}
                </h2>

                <div class="mt-section grid gap-12 sm:grid-cols-[minmax(0,0.8fr)_minmax(0,1.2fr)] sm:gap-16">
                    <figure class="sm:sticky sm:top-6 sm:self-start">
                        <div class="aspect-3/4 overflow-hidden rounded-t-full rounded-b-card bg-brand-100">
                            @if (!empty($section_data['photo']))
                                <img src="{{ asset($section_data['photo']) }}"
                                     alt="{{ $section_data['photo_alt'] ?? ('Portrait of ' . ($section_data['name'] ?? '')) }}"
                                     width="900"
                                     height="1200"
                                     loading="lazy"
                                     decoding="async"
                                     class="size-full object-cover object-top">
                            @else
                                <div aria-hidden="true" class="grid size-full place-items-center font-display text-5xl font-extrabold text-brand-500/40">
                                    {{ mb_strtoupper(mb_substr($section_data['name'] ?? 'A', 0, 1)) }}
                                </div>
                            @endif
                        </div>
                    </figure>

                    <div>
                        <p class="font-display text-2xl font-extrabold">{{ $section_data['intro'] ?? '' }}</p>

                        @foreach ($section_data['paragraphs'] ?? [] as $paragraph)
                            <p class="mt-6 max-w-prose text-base text-panel-foreground/70">{{ $paragraph }}</p>
                        @endforeach

                        <dl class="mt-12 grid gap-6 border-t border-panel-line pt-5 sm:grid-cols-3">
                            @foreach ($section_data['facts'] ?? [] as $fact)
                                <div>
                                    <dt class="label text-panel-foreground/70">{{ $fact['label'] }}</dt>
                                    <dd class="mt-2 text-base">{{ $fact['value'] }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    </div>
                </div>

                <div class="mt-section grid gap-8 sm:grid-cols-[minmax(0,0.8fr)_minmax(0,1.2fr)] sm:gap-16">
                    <h3 class="label text-panel-foreground/70">({{ $section_data['services_label'] ?? 'What I do' }})</h3>

                    <ol>
                        @foreach ($section_data['services'] ?? [] as $service)
                            <li class="group grid gap-2 border-t border-panel-line py-8 last:border-b sm:grid-cols-[4rem_1fr] sm:gap-6">
                                <span aria-hidden="true" class="label text-panel-foreground/70">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <div>
                                    <h4 class="font-display text-2xl font-extrabold transition-transform duration-(--duration-base) ease-smooth group-hover:translate-x-3">
                                        {{ $service['title'] }}
                                    </h4>
                                    <p class="mt-3 max-w-prose text-base text-panel-foreground/70">{{ $service['text'] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>

                <div class="mt-section grid gap-12 sm:grid-cols-2 sm:gap-16">
                    <div>
                        <h3 class="label text-panel-foreground/70">({{ $section_data['stack_label'] ?? 'Stack' }})</h3>
                        <ul class="mt-6 flex flex-wrap gap-2">
                            @foreach ($section_data['stack'] ?? [] as $item)
                                <li class="chip">{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <div>
                        <h3 class="label text-panel-foreground/70">({{ $section_data['experience_label'] ?? 'Experience' }})</h3>
                        <ul class="mt-6">
                            @foreach ($section_data['experience'] ?? [] as $item)
                                <li class="flex flex-wrap items-baseline justify-between gap-x-6 gap-y-1 border-t border-panel-line py-5 last:border-b">
                                    <div>
                                        <p class="text-base">{{ $item['role'] }}</p>
                                        <p class="text-sm text-panel-foreground/70">{{ $item['company'] }}</p>
                                    </div>
                                    <span class="label text-panel-foreground/70">{{ $item['years'] }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                @if (!empty($section_data['cta']))
                    <div class="mt-section flex flex-wrap items-center justify-between gap-6 border-t border-panel-line pt-10">
                        <p class="max-w-[18ch] font-display text-3xl font-extrabold">{{ $section_data['cta']['text'] ?? '' }}</p>
                        <a href="{{ $section_data['cta']['href'] }}" class="btn border-brand-500 bg-brand-500 text-white">{{ $section_data['cta']['label'] }}</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>