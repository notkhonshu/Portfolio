@php
    $socialLinks = collect([
        'Instagram' => config('socials.instagram.url'),
        'GitHub'    => config('socials.github.url'),
        'LinkedIn'  => config('socials.linkedin.url'),
    ])->filter();
@endphp

<footer class="pointer-events-none fixed inset-x-0 bottom-0 z-(--z-overlay) flex flex-col items-center gap-3.5 p-gutter text-center text-foreground mix-blend-difference sm:grid sm:grid-cols-[1fr_auto_1fr] sm:items-end sm:gap-6 sm:text-left">
    <p class="max-w-70 text-sm sm:max-w-[21.25rem]">Web developer in Bali building fast, responsive websites for businesses and brands.</p>

    <div aria-hidden="true" class="label hidden text-center sm:block sm:justify-self-center">Scroll</div>

    <div class="label flex flex-wrap justify-center gap-6 sm:justify-end sm:justify-self-end">
        @if ($socialLinks->isNotEmpty())
            <nav aria-label="Social media">
                <ul class="flex flex-wrap justify-center gap-6">
                    @foreach ($socialLinks as $label => $href)
                        <li>
                            <a href="{{ $href }}"
                               class="pointer-events-auto transition-opacity duration-(--duration-fast) hover:opacity-60"
                               target="_blank"
                               rel="me noopener noreferrer"
                               aria-label="{{ config('metadata.author') }} on {{ $label }} (opens in a new tab)">{{ $label }}</a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        @endif

        <span>© {{ date('Y') }}</span>
    </div>
</footer>