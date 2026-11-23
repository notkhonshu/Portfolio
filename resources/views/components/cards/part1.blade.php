<a href="{{ route('projects.show', $project['slug']) }}" data-project-open="{{ $project['slug'] }}" class="group block w-(--card-width-mobile) shrink-0 sm:w-(--card-width) sm:odd:-mt-[12svh] sm:even:mt-[12svh]">
    <div class="aspect-3/4 overflow-hidden rounded-card bg-brand-900 ring-1 ring-inset ring-line">
        <img src="{{ asset($project['feature_images']) }}"
             alt="{{ $project['title'] }}"
             loading="lazy"
             decoding="async"
             class="size-full object-cover object-top transition-transform duration-(--duration-slow) ease-smooth group-hover:scale-105">
    </div>

    <div class="mt-3.5 flex items-baseline justify-between gap-3 text-base font-medium">
        <span>{{ $project['title'] }}</span>
        <span class="label text-muted">{{ $project['years'] }}</span>
    </div>
</a>