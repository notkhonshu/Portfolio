@include('partials.header')
<body>
    <div class="isolate">
        @include('partials.navbar')

        @yield('content')

        @include('partials.footer')
    </div>
    @include('partials.script')
</body>