@section('title', "{$code} — {$title} | " . config('metadata.author'))
@section('description', $message)

@push('head')
    <meta name="robots" content="noindex, nofollow">
@endpush

@include('partials.header')
<body>
    <div class="isolate">
        <main class="flex h-svh flex-col justify-between px-gutter py-6">
            <header class="label flex items-center justify-between">
                <span>(Error)</span>
                <span class="text-muted">{{ config('socials.names') }}</span>
            </header>

            <div class="text-center">
                <h1 class="pr-[0.06em] font-display text-wordmark font-extrabold lowercase">{{ $code }}</h1>
                <p class="mt-6 text-base text-muted">{{ $message }}</p>
            </div>

            <div class="flex min-h-12 justify-center">
                @if ($code !== 503)
                    <a href="{{ url('/') }}" class="btn btn-solid">Back to home</a>
                @endif
            </div>
        </main>
    </div>
    @include('partials.script')
</body>