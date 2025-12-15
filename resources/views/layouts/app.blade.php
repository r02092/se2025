<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8" />
    <title>@yield('title', 'SceneTrip')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/MainFrame.css') }}" />
    @yield('styles')
    @stack('styles')
</head>
<body>
    <div class="container">
        @include('layouts.partials.header')

        @yield('content')

        @include('layouts.partials.bottom-bar')

        <div class="modal-overlay" id="modal-overlay"></div>
        <div class="modal" id="modal">
            <button class="close-modal" id="close-modal" aria-label="閉じる">
                ×
            </button>
            <div class="modal-content" id="modal-content"></div>
        </div>
    </div>

    @yield('scripts')
    @stack('scripts')
</body>
</html>
