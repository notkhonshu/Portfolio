<div class="pointer-events-none fixed inset-0 z-(--z-overlay) flex flex-col justify-between p-gutter text-foreground">
    <header class="grid grid-cols-[1fr_auto_1fr] items-center gap-2 sm:flex sm:justify-between sm:gap-4">
        <a href="{{ url('/') }}" class="pointer-events-auto col-start-2 row-start-1 flex items-center gap-2.5 justify-self-center transition-opacity duration-(--duration-fast) hover:opacity-60 sm:col-auto sm:row-auto sm:justify-self-auto" aria-label="{{ config('metadata.site_name') }} — Home">
            <span class="label hidden sm:inline">Web developer &amp; designer</span>
        </a>

        <nav class="contents sm:flex sm:items-center sm:gap-3" aria-label="Main navigation">
            <button id="aboutBtn" type="button" class="btn pointer-events-auto col-start-1 row-start-1 justify-self-start px-4 py-2 font-mono text-label font-normal tracking-label uppercase hover:opacity-60 sm:col-auto sm:row-auto sm:justify-self-auto sm:px-5.5 sm:py-2.5" aria-haspopup="dialog" aria-controls="aboutPanel">About Me</button>
            <a href="{{ config('socials.contact.url_email')}}" class="btn btn-solid pointer-events-auto col-start-3 row-start-1 justify-self-end px-4 py-2 font-mono text-label font-normal tracking-label uppercase hover:opacity-60 sm:col-auto sm:row-auto sm:justify-self-auto sm:px-5.5 sm:py-2.5">Let’s talk</a>
        </nav>
    </header>
</div>