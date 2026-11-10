<footer class="pointer-events-none fixed inset-x-0 bottom-0 z-(--z-overlay) flex flex-col items-center gap-3.5 p-gutter text-center text-foreground mix-blend-difference sm:grid sm:grid-cols-[1fr_auto_1fr] sm:items-end sm:gap-6 sm:text-left">
    <p class="max-w-70 text-sm sm:max-w-[21.25rem]">Frontend web developer and designer crafting distinctive websites for hospitality, travel and local brands.</p>

    <div class="label hidden text-center sm:block sm:justify-self-center">Scroll</div>

    <div class="label flex flex-wrap justify-center gap-6 sm:justify-end sm:justify-self-end">
        <a href="{{ config('socials.instagram.url', '#') }}" class="pointer-events-auto transition-opacity duration-(--duration-fast) hover:opacity-60" target="_blank" rel="noopener noreferrer">Instagram</a>
        <a href="{{ config('socials.github.url', '#') }}" class="pointer-events-auto transition-opacity duration-(--duration-fast) hover:opacity-60" target="_blank" rel="noopener noreferrer">GitHub</a>
        <span>© {{ date('Y') }}</span>
    </div>
</footer>