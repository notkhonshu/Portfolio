@php
    $projects = $section_data['data'];
@endphp

<section id="portfolio" data-portfolio class="relative isolate h-svh overflow-hidden text-foreground">
    <div data-portfolio-track class="absolute inset-x-0 top-0 flex flex-col items-center gap-14 pt-[22svh] will-change-transform sm:inset-x-auto sm:inset-y-0 sm:left-0 sm:w-max sm:flex-row sm:gap-card-gap sm:pl-gutter sm:pt-0">
        @foreach ($projects as $project)
            @include('components.cards.part1', ['project' => $project])
        @endforeach
    </div>

    <h1 class="pointer-events-none absolute inset-0 z-10 grid -translate-y-[0.1em] select-none place-items-center pr-[0.06em] text-center font-display text-wordmark font-extrabold text-white lowercase">
        {{ config('socials.names') }}
    </h1>
</section>